<?php

use PHPUnit\Framework\TestCase;
use Marcuwynu23\PHPInspector\PHPInspector;


class PHPInspectorTest extends TestCase
{
    public function testLogClass()
    {
        $this->expectNotToPerformAssertions(); // Just test it runs without errors
        PHPInspector::log(\DateTime::class);
    }

    public function testLogObject()
    {
        $obj = new stdClass();
        $obj->foo = 'bar';
        $this->expectNotToPerformAssertions();
        PHPInspector::log($obj);
    }
}
