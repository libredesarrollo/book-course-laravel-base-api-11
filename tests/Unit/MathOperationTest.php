<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MathOperations{
    function add($a, $b)  {
        return $a+$b;
    }
    function subtrart($a, $b)  {
        return $a-$b;
    }
    function multiply($a, $b)  {
        return $a*$b;
    }
    function divide($a, $b)  {
        return $a/$b;
    }
}

class MathOperationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testAdd(): void
    {
        $mathOperations = new MathOperations();
        $result = $mathOperations->add(2,2);
        
        $this->assertEquals(4,$result); // $this->assertTrue(5==$result);
    }
    public function testAddNegativeNumber(): void
    {
        $mathOperations = new MathOperations();
        $result = $mathOperations->add(2,-2);
        
        $this->assertEquals(0,$result); // $this->assertTrue(5==$result);
    }
    public function testSubtraction(): void
    {
        $mathOperations = new MathOperations();
        $result = $mathOperations->subtrart(2,1);
        
        $this->assertEquals(1,$result); // $this->assertTrue(5==$result);
    }
    public function testMutiply(): void
    {
        $mathOperations = new MathOperations();
        $result = $mathOperations->multiply(4,2);
        
        $this->assertEquals(8,$result); // $this->assertTrue(5==$result);
    }
    public function testDivide(): void
    {
        $mathOperations = new MathOperations();
        $result = $mathOperations->divide(4,2);
        
        $this->assertEquals(2,$result); // $this->assertTrue(5==$result);
    }

}
