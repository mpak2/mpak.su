<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_CommonTest extends Services_Facebook_UnitTestCommon
{

    public function testCallMethod()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?><test_foo_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</test_foo_response>
XML;
        $this->mockSendRequest($response);
        $args = array(
            'session_key' => 'sekrtsessionkey',
            'foo' => 'bar'
        );

        $result = $this->instance->callMethod('test.unit', $args);
        $this->assertType('SimpleXMLElement', $result);

        $result = $this->instance->callMethod('test.unit', $args);
    }

    /**
     * @expectedException Services_Facebook_Exception
     */
    public function testParseReponseEmpty()
    {
        $this->mockSendRequest('');
        $args = array(
            'session_key' => 'sekrtsessionkey',
            'foo' => 'bar',
            'sig' => 'foo'
        );

        $result = $this->instance->callMethod('test.unit', $args);
 
        $this->instance->parseResponse('');
    }

    /**
     * @expectedException Services_Facebook_Exception
     */
    public function testParseResponseError()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<error_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">
  <error_code>10</error_code>
  <error_msg>Application does not have permission for this action</error_msg>
  <request_args list="true">
    <arg>
      <key>api_key</key>
      <value>a98c98b2b46739d500cae76cf9764820</value>
    </arg>
    <arg>
      <key>call_id</key>
      <value>1232128764.68</value>
    </arg>
    <arg>
      <key>format</key>
      <value>XML</value>
    </arg>
    <arg>
      <key>method</key>
      <value>friends.get</value>
    </arg>
    <arg>
      <key>uid</key>
      <value>617370918</value>
    </arg>
    <arg>
      <key>v</key>
      <value>1.0</value>
    </arg>
    <arg>
      <key>sig</key>
      <value>304aa10271f333b5da2f21378975cbf8</value>
    </arg>
  </request_args>
</error_response>
XML;

        $this->mockSendRequest($response);
        $args = array(
            'session_key' => 'sekrtsessionkey',
            'foo' => 'bar'
        );

        $result = $this->instance->callMethod('unit.test1', $args);
    }

}

?>
