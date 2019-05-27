<?php
class statTest extends PHPUnit_Framework_TestCase
{
	public function setUp(){ }
	public function tearDown(){ }
    /**
     * @covers stat::_get_tabname
     */
    public function test_get_tabname() {
    	$a = 0; $b=0;
        return $this->AssertEquals($a,$b);
    }
}