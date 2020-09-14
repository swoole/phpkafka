<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Protocol;

use Longyan\Kafka\Protocol\Type\Boolean;
use Longyan\Kafka\Protocol\Type\CompactString;
use Longyan\Kafka\Protocol\Type\Float64;
use Longyan\Kafka\Protocol\Type\Int16;
use Longyan\Kafka\Protocol\Type\Int32;
use Longyan\Kafka\Protocol\Type\Int64;
use Longyan\Kafka\Protocol\Type\Int8;
use Longyan\Kafka\Protocol\Type\String16;
use Longyan\Kafka\Protocol\Type\UInt32;
use Longyan\Kafka\Protocol\Type\VarInt;
use Longyan\Kafka\Protocol\Type\VarLong;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    private const TEST_STRING = 'PHP is the best programming language in the world';

    private const INT8_MIN = -128;

    private const INT8_MAX = 127;

    private const INT16_MIN = -32768;

    private const INT16_MAX = 32767;

    private const INT32_MIN = -2147483648;

    private const INT32_MAX = 2147483647;

    private const UINT32_MIN = 0;

    private const UINT32_MAX = 2147483648;

    private const INT64_MIN = '-9223372036854775808';

    private const INT64_MAX = '9223372036854775807';

    private const FLOAT_VALUE = 3.141592654;

    public function testBoolean()
    {
        $encodeResult = Boolean::pack(true);
        $this->assertEquals(\chr(1), $encodeResult);
        $this->assertTrue(Boolean::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);

        $encodeResult = Boolean::pack(false);
        $this->assertEquals(\chr(0), $encodeResult);
        $this->assertFalse(Boolean::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);
    }

    public function testCompactString()
    {
        $encodeResult = CompactString::pack(self::TEST_STRING);
        $this->assertEquals('e04e99b06d5c38f7c9bf0b1fa5706419', md5($encodeResult));
        $this->assertEquals(self::TEST_STRING, CompactString::unpack($encodeResult, $size));
        $this->assertEquals(1 + \strlen(self::TEST_STRING), $size);
    }

    public function testFloat64()
    {
        $encodeResult = Float64::pack(self::FLOAT_VALUE);
        $this->assertEquals(8, \strlen($encodeResult));
        $this->assertEquals(self::FLOAT_VALUE, Float64::unpack($encodeResult, $size));
        $this->assertEquals(8, $size);
    }

    public function testInt8()
    {
        $encodeResult = Int8::pack(self::INT8_MIN);
        $this->assertEquals(1, \strlen($encodeResult));
        $this->assertEquals(self::INT8_MIN, Int8::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);

        $encodeResult = Int8::pack(self::INT8_MAX);
        $this->assertEquals(1, \strlen($encodeResult));
        $this->assertEquals(self::INT8_MAX, Int8::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);
    }

    public function testInt16()
    {
        $encodeResult = Int16::pack(self::INT16_MIN);
        $this->assertEquals(2, \strlen($encodeResult));
        $this->assertEquals(self::INT16_MIN, Int16::unpack($encodeResult, $size));
        $this->assertEquals(2, $size);

        $encodeResult = Int16::pack(self::INT16_MAX);
        $this->assertEquals(2, \strlen($encodeResult));
        $this->assertEquals(self::INT16_MAX, Int16::unpack($encodeResult, $size));
        $this->assertEquals(2, $size);
    }

    public function testInt32()
    {
        $encodeResult = Int32::pack(self::INT32_MIN);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(self::INT32_MIN, Int32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);

        $encodeResult = Int32::pack(self::INT32_MAX);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(self::INT32_MAX, Int32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);
    }

    public function testInt64()
    {
        $encodeResult = Int64::pack(self::INT64_MIN);
        $this->assertEquals(8, \strlen($encodeResult));
        $this->assertEquals(self::INT64_MIN, Int64::unpack($encodeResult, $size));
        $this->assertEquals(8, $size);

        $encodeResult = Int64::pack(self::INT64_MAX);
        $this->assertEquals(8, \strlen($encodeResult));
        $this->assertEquals(self::INT64_MAX, Int64::unpack($encodeResult, $size));
        $this->assertEquals(8, $size);
    }

    public function testString16()
    {
        $encodeResult = String16::pack(self::TEST_STRING);
        $this->assertEquals('538e48ac43305ec50db83ea143470770', md5($encodeResult));
        $this->assertEquals(self::TEST_STRING, String16::unpack($encodeResult, $size));
        $this->assertEquals(2 + \strlen(self::TEST_STRING), $size);
    }

    public function testUInt32()
    {
        $encodeResult = UInt32::pack(self::UINT32_MIN);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(self::UINT32_MIN, UInt32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);

        $encodeResult = UInt32::pack(self::UINT32_MAX);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(self::UINT32_MAX, UInt32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);
    }

    public function testVarInt()
    {
        foreach ([
            self::INT32_MIN => 10,
            self::INT32_MAX => 5,
            2 => 1,
            16383 => 2,
            2097151 => 3,
            268435455 => 4,
        ] as $number => $exceptedSize) {
            $encodeResult = VarInt::pack($number);
            $this->assertEquals($number, VarInt::unpack($encodeResult, $size));
            $this->assertEquals($exceptedSize, $size, 'number ' . $number);
        }
    }

    public function testVarLong()
    {
        foreach ([
            self::INT32_MIN => 10,
            self::INT32_MAX => 5,
            self::INT64_MIN => 10,
            self::INT64_MAX => 9,
            2 => 1,
            16383 => 2,
            2097151 => 3,
            268435455 => 4,
            34359738367 => 5,
            4398046511103 => 6,
            562949953421311 => 7,
            72057594037927935 => 8,
        ] as $number => $exceptedSize) {
            $encodeResult = VarLong::pack($number);
            $this->assertEquals($number, VarLong::unpack($encodeResult, $size));
            $this->assertEquals($exceptedSize, $size, 'number ' . $number);
        }
    }
}
