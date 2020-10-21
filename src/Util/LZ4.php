<?php

declare(strict_types=1);

namespace longlang\phpkafka\Util;

use longlang\phpkafka\Protocol\ProtocolUtil;

/**
 * LZ4 frame format.
 *
 * @see https://github.com/lz4/lz4/blob/master/doc/lz4_Frame_format.md
 * @see https://github.com/Timmerito/PLCVKuriimu/blob/master/src/Kontract/Compression/LZ4.cs
 */
class LZ4
{
    public const LZ4_MAGIC = 0x184D2204;

    public const BLOCK_MAX_SIZE_64KB = 4;

    public const BLOCK_MAX_SIZE_256KB = 5;

    public const BLOCK_MAX_SIZE_1MB = 6;

    public const BLOCK_MAX_SIZE_4MB = 7;

    private function __construct()
    {
    }

    /**
     * encode data to lz4 frame format.
     */
    public static function compress(string $data, bool $blockIndep = true, bool $blockChecksum = false, bool $contentSize = false, bool $contentChecksum = true, bool $dictID = false, int $blockMaxSize = self::BLOCK_MAX_SIZE_4MB): string
    {
        if (\function_exists('lz4_compress')) {
            $block = substr(lz4_compress($data), 4);
        } else {
            throw new \RuntimeException('Please install and enable lz4 extension first: https://github.com/kjdev/php-ext-lz4');
        }
        $result = '';
        // Magic
        $result .= pack('V', self::LZ4_MAGIC);
        // FLG Byte
        $flg = 0x40;
        if ($blockIndep) {
            $flg |= 0x20;
        }
        if ($blockChecksum) {
            $flg |= 0x10;
        }
        if ($contentSize) {
            $flg |= 0x08;
        }
        if ($contentChecksum) {
            $flg |= 0x04;
        }
        if ($dictID) {
            $flg |= 0x01;
        }
        $result .= \chr($flg);
        // BD Byte
        $bd = $blockMaxSize << 4;
        $result .= \chr($bd);
        // Content Size
        if ($contentSize) {
            $result .= pack('P', \strlen($data));
        }
        // Dictionary - STUB since Dictionary usage isn't understood in LZ4
        if ($dictID) {
            $result .= pack('V', 0);
        }
        // XXHash32
        $hash = new \exussum12\xxhash\V32();
        $fieldDesc = substr($result, 4);
        $hc = ProtocolUtil::shr32(ProtocolUtil::uint32((int) base_convert($hash->hash($fieldDesc), 16, 10)), 8) & 0xFF;
        $result .= \chr($hc);
        // Write Block Data
        $result .= pack('V', \strlen($block)) . $block;
        if ($blockChecksum) {
            $result .= pack('V', base_convert($hash->hash($block), 16, 10));
        }
        //End Mark
        $result .= pack('V', 0);
        //Content checksum
        if ($contentChecksum) {
            $result .= pack('V', base_convert($hash->hash($data), 16, 10));
        }

        return $result;
    }

    /**
     * decode frame format to data.
     */
    public static function uncompress(string $data, bool $precedingSize = false): string
    {
        if (!\function_exists('lz4_uncompress')) {
            throw new \RuntimeException('Please install and enable lz4 extension first: https://github.com/kjdev/php-ext-lz4');
        }
        $position = 0;
        $decompressedSize = 0;
        if ($precedingSize) {
            $decompressedSize = unpack('V', substr($data, $position, 4))[1];
            $position += 4;
        }

        $magic = unpack('V', substr($data, $position, 4))[1];
        $position += 4;
        if (self::LZ4_MAGIC !== $magic) {
            throw new \RuntimeException('Invalid LZ4 magic');
        }

        $flg = \ord($data[$position]);
        ++$position;
        if (1 !== ($flg >> 6)) {
            throw new \RuntimeException(sprintf('Unsupported version %s', $flg >> 6));
        }

        $blockIndep = 1 == (($flg >> 5) & 1);
        $blockChecksum = 1 == (($flg >> 4) & 1);
        $contentSize = 1 == (($flg >> 3) & 1);
        $contentChecksum = 1 == (($flg >> 2) & 1);
        $dictID = 1 == ($flg & 1);

        $bd = \ord($data[$position]);
        ++$position;
        $blockMaxSize = ($bd >> 4) & 0x7;

        if ($contentSize) {
            $position += 8;
        }

        if ($dictID) {
            $position += 4;
        }

        $headerLength = 3 + ($contentSize ? 8 : 0) + ($dictID ? 4 : 0);
        $header = substr($data, $position - ($headerLength - 1), $headerLength - 1);
        $hc = \ord($data[$position]);
        ++$position;
        // XXHash32
        $hash = new \exussum12\xxhash\V32();
        if ($hc !== (ProtocolUtil::shr32(ProtocolUtil::uint32((int) base_convert($hash->hash($header), 16, 10)), 8) & 0xFF)) {
            throw new \RuntimeException('Header checksum is invalid');
        }

        // Blocks
        $result = '';
        $length = \strlen($data);
        $contentChecksumLength = $contentChecksum ? 4 : 0;
        while ($position < $length - $contentChecksumLength) {
            $blockSize = unpack('V', substr($data, $position, 4))[1];
            $position += 4;
            while (0 !== $blockSize) {
                $compData = substr($data, $position, $blockSize);
                $position += $blockSize;
                $decomp = lz4_uncompress(pack('V', \strlen($compData)) . $compData);
                if ($blockChecksum) {
                    $check = unpack('V', substr($data, $position, 4))[1];
                    $position += 4;
                    if ($check !== (int) base_convert($hash->hash($compData), 16, 10)) {
                        throw new \RuntimeException('Block checksum was invalid');
                    }
                }
                $result .= $decomp;
                $blockSize = unpack('V', substr($data, $position, 4))[1];
                $position += 4;
            }
        }

        if ($contentChecksum) {
            $checkSum = unpack('V', substr($data, $position, 4))[1];
            $position += 4;
            if ($checkSum !== (int) base_convert($hash->hash($result), 16, 10)) {
                throw new \RuntimeException('Decompressed data is corrupted');
            }
        }
        if ($precedingSize && $decompressedSize !== $length) {
            throw new \RuntimeException('Preceding decompressed size doesn\'t match');
        }

        return $result;
    }
}
