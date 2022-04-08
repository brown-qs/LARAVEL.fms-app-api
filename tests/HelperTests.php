<?php

use Carbon\Carbon;

class HelperTests extends TestCase
{
    public function testCarbonTimestampFunction()
    {
        $this->assertEquals(true, is_int(carbon_timestamp(new Carbon())));
        $this->assertEquals(null, carbon_timestamp(null));
    }

    public function testCarbonDateFunction()
    {
        $this->assertNull(carbon_date('0000-00-00'));
        $this->assertEquals(true, is_int(carbon_date('2012-12-12')));
        $this->assertNull(carbon_date(null));
    }

    public function testScorpionPasswordHashFunction()
    {
        $this->assertEquals(sha1('password' . 'test'), scorpion_password_hash('password', 'test'));
    }

    public function testScorpionPasswordVerifyFunction()
    {
        $this->assertEquals(true, scorpion_password_verify('password', 'hash', sha1('password' . 'hash')));
    }

    public function testScorpionUniqueHashFunction()
    {
        $this->assertNotSame(scorpion_unique_hash(), scorpion_unique_hash());
    }
}
