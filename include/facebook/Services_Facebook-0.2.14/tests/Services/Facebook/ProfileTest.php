<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_ProfileTest extends Services_Facebook_UnitTestCommon
{

    public function testSetFBML()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<profile_setFBML_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</profile_setFBML_response>
XML;

        $this->mockSendRequest($response);
        $fbml = array(
            'profile' => 'foo',
            'profile_action' => 'foo',
            'mobile_profile' => 'foo'
        );

        $this->assertTrue($this->instance->setFBML($fbml, 123));
    }

    public function testGetFBML()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<profile_getFBML_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">&lt;fb:fbml version=&quot;1.1&quot;&gt;Services Facebook yay!&lt;/fb:fbml&gt;</profile_getFBML_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getFBML(120);
        $this->assertEquals('<fb:fbml version="1.1">Services Facebook yay!</fb:fbml>', $result);
    }

}

?>
