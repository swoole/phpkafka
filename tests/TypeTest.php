<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Protocol\Type\ArrayInt32;
use longlang\phpkafka\Protocol\Type\Boolean;
use longlang\phpkafka\Protocol\Type\CompactArray;
use longlang\phpkafka\Protocol\Type\CompactNullableString;
use longlang\phpkafka\Protocol\Type\CompactString;
use longlang\phpkafka\Protocol\Type\Float64;
use longlang\phpkafka\Protocol\Type\Int16;
use longlang\phpkafka\Protocol\Type\Int32;
use longlang\phpkafka\Protocol\Type\Int64;
use longlang\phpkafka\Protocol\Type\Int8;
use longlang\phpkafka\Protocol\Type\NullableString;
use longlang\phpkafka\Protocol\Type\String16;
use longlang\phpkafka\Protocol\Type\UInt32;
use longlang\phpkafka\Protocol\Type\UVarInt;
use longlang\phpkafka\Protocol\Type\VarInt;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    private const TEST_STRING = 'PHP is the best programming language in the world';

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
        $this->assertEquals('325048502069732074686520626573742070726f6772616d6d696e67206c616e677561676520696e2074686520776f726c64', bin2hex($encodeResult));
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
        $encodeResult = Int8::pack(Int8::MIN_VALUE);
        $this->assertEquals(1, \strlen($encodeResult));
        $this->assertEquals(Int8::MIN_VALUE, Int8::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);

        $encodeResult = Int8::pack(Int8::MAX_VALUE);
        $this->assertEquals(1, \strlen($encodeResult));
        $this->assertEquals(Int8::MAX_VALUE, Int8::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);
    }

    public function testInt16()
    {
        $encodeResult = Int16::pack(Int16::MIN_VALUE);
        $this->assertEquals(2, \strlen($encodeResult));
        $this->assertEquals(Int16::MIN_VALUE, Int16::unpack($encodeResult, $size));
        $this->assertEquals(2, $size);

        $encodeResult = Int16::pack(Int16::MAX_VALUE);
        $this->assertEquals(2, \strlen($encodeResult));
        $this->assertEquals(Int16::MAX_VALUE, Int16::unpack($encodeResult, $size));
        $this->assertEquals(2, $size);
    }

    public function testInt32()
    {
        $encodeResult = Int32::pack(Int32::MIN_VALUE);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(Int32::MIN_VALUE, Int32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);

        $encodeResult = Int32::pack(Int32::MAX_VALUE);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(Int32::MAX_VALUE, Int32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);
    }

    public function testInt64()
    {
        $encodeResult = Int64::pack(Int64::MIN_VALUE);
        $this->assertEquals(8, \strlen($encodeResult));
        $this->assertEquals(Int64::MIN_VALUE, Int64::unpack($encodeResult, $size));
        $this->assertEquals(8, $size);

        $encodeResult = Int64::pack(Int64::MAX_VALUE);
        $this->assertEquals(8, \strlen($encodeResult));
        $this->assertEquals(Int64::MAX_VALUE, Int64::unpack($encodeResult, $size));
        $this->assertEquals(8, $size);
    }

    public function testNullableString()
    {
        $encodeResult = NullableString::pack(self::TEST_STRING);
        $this->assertEquals('00315048502069732074686520626573742070726f6772616d6d696e67206c616e677561676520696e2074686520776f726c64', bin2hex($encodeResult));
        $this->assertEquals(self::TEST_STRING, NullableString::unpack($encodeResult, $size));
        $this->assertEquals(2 + \strlen(self::TEST_STRING), $size);

        $encodeResult = NullableString::pack(null);
        $this->assertEquals('ffff', bin2hex($encodeResult));
        $this->assertNull(NullableString::unpack($encodeResult, $size));
        $this->assertEquals(2, $size);
    }

    public function testCompactNullableString()
    {
        $encodeResult = CompactNullableString::pack(self::TEST_STRING);
        $this->assertEquals('325048502069732074686520626573742070726f6772616d6d696e67206c616e677561676520696e2074686520776f726c64', bin2hex($encodeResult));
        $this->assertEquals(self::TEST_STRING, CompactNullableString::unpack($encodeResult, $size));
        $this->assertEquals(1 + \strlen(self::TEST_STRING), $size);

        $encodeResult = CompactNullableString::pack(null);
        $this->assertEquals('00', bin2hex($encodeResult));
        $this->assertNull(CompactNullableString::unpack($encodeResult, $size));
        $this->assertEquals(1, $size);
    }

    public function testString16()
    {
        $encodeResult = String16::pack(self::TEST_STRING);
        $this->assertEquals('00315048502069732074686520626573742070726f6772616d6d696e67206c616e677561676520696e2074686520776f726c64', bin2hex($encodeResult));
        $this->assertEquals(self::TEST_STRING, String16::unpack($encodeResult, $size));
        $this->assertEquals(2 + \strlen(self::TEST_STRING), $size);
    }

    public function testUInt32()
    {
        $encodeResult = UInt32::pack(UInt32::MIN_VALUE);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(UInt32::MIN_VALUE, UInt32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);

        $encodeResult = UInt32::pack(UInt32::MAX_VALUE);
        $this->assertEquals(4, \strlen($encodeResult));
        $this->assertEquals(UInt32::MAX_VALUE, UInt32::unpack($encodeResult, $size));
        $this->assertEquals(4, $size);
    }

    public function testUVarInt()
    {
        foreach ([
            UVarInt::MIN_VALUE => 1,
            UVarInt::MAX_VALUE => 5,
            2 => 1,
            16383 => 2,
            2097151 => 3,
            268435455 => 4,
        ] as $number => $exceptedSize) {
            $encodeResult = UVarInt::pack($number);
            $this->assertEquals($number, UVarInt::unpack($encodeResult, $size));
            $this->assertEquals($exceptedSize, $size, 'number ' . $number);
        }
    }

    public function testVarInt()
    {
        foreach ([
            VarInt::MIN_VALUE => 5,
            VarInt::MAX_VALUE => 5,
            2 => 1,
            16383 => 3,
            2097151 => 4,
            268435455 => 5,
        ] as $number => $exceptedSize) {
            $encodeResult = VarInt::pack($number);
            $this->assertEquals($exceptedSize, \strlen($encodeResult), 'number ' . $number);
            $this->assertEquals($number, VarInt::unpack($encodeResult, $size));
            $this->assertEquals($exceptedSize, $size, 'number ' . $number);
        }
    }

    public function testArrayInt32()
    {
        $exceptedArray = [1, 2, 3];
        $encodeResult = ArrayInt32::pack($exceptedArray, Int32::class);
        $this->assertEquals('00000003000000010000000200000003', bin2hex($encodeResult));
        $this->assertEquals($exceptedArray, ArrayInt32::unpack($encodeResult, $size, Int32::class));
        $this->assertEquals(16, $size);

        $exceptedArray = null;
        $encodeResult = ArrayInt32::pack($exceptedArray, Int32::class);
        $this->assertEquals('ffffffff', bin2hex($encodeResult));
        $this->assertEquals($exceptedArray, ArrayInt32::unpack($encodeResult, $size, Int32::class));
        $this->assertEquals(4, $size);
    }

    public function testCompactArray()
    {
        $exceptedArray = [1, 2, 3];
        $encodeResult = CompactArray::pack($exceptedArray, Int32::class);
        $this->assertEquals('04000000010000000200000003', bin2hex($encodeResult));
        $this->assertEquals($exceptedArray, CompactArray::unpack($encodeResult, $size, Int32::class));
        $this->assertEquals(13, $size);
    }
}
