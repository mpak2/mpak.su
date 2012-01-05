<?php

set_include_path(get_include_path() . ':../../../');

require_once 'PHPUnit/Framework.php';
require_once 'Services/Facebook.php';

abstract class Services_Facebook_UnitTestCommon extends PHPUnit_Framework_TestCase
{

    protected $instance = null;

    protected $mock = array('sendRequest');

    protected function setUp()
    {
        $class = str_replace('Test', '', get_class($this));
        include_once str_replace('_', '/', $class . '.php');

        Services_Facebook::$apiKey = 'foo';
        Services_Facebook::$secret = 'bar';

        $this->instance = $this->getMock($class, $this->mock);
    }

    protected function mockSendRequest($response)
    {
        $this->instance->expects($this->any())
                        ->method('sendRequest')
                        ->will($this->returnValue($response));
    }

    protected function tearDown()
    {
        $this->instance = null;
        Services_Facebook::$apiKey  = null;
        Services_Facebook::$secret  = null;
    }
}

?>
