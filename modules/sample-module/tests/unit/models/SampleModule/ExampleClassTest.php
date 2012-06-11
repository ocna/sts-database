<?php
/**
 * Naviance, Inc
 *
 * @category    Naviance
 * @package     
 * @subpackage    
 * @copyright   Copyright (c) 2012 Naviance, Inc (www.naviance.com)
 * @license     This source file is the property Naviance, Inc and may not be redistributed in part or its entirty without the expressed written consent of Naviance, Inc.
 * @author      Matthew Caya <matthew.caya@naviance.com>
 */
class SampleModule_ExampleClassTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @group sample-module-example
     */
    public function returnValueProvidedForNoReason()
    {
        $class = new SampleModule_ExampleClass();
        $value = $class->returnValueProvided("value");
        $this->assertEquals($value, "value", "returnValueProvided");
    }
}