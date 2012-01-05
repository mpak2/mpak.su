<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_AdminTest extends Services_Facebook_UnitTestCommon
{

    public function testGetAppProperties()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <admin_getAppProperties_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/http://api.facebook.com/1.0/facebook.xsd">  [{"application_name": "Jeff is cool"}, {"dev_mode": 0}] </admin_getAppProperties_response>
XML;

        $this->mockSendRequest($response);
        $args = array(
            'application_name',
            'dev_mode'
        );

        $result = $this->instance->getAppProperties($args);
        $this->assertEquals('Jeff is cool', $result[0]->application_name);
        $this->assertEquals(0, $result[1]->dev_mode);

        $result = $this->instance->getAppProperties();
        $this->assertEquals('Jeff is cool', $result[0]->application_name);
        $this->assertEquals(0, $result[1]->dev_mode);
    }

    public function testSetAppProperties()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<admin_setAppProperties_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</admin_setAppProperties_response>
XML;

        $this->mockSendRequest($response);
        $args = array(
            'dev_mode' => 1
        );

        $result = $this->instance->setAppProperties($args);
        $this->assertTrue($result);
    }

    public function testGetNotificationsPerDay()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<admin_getAllocation_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">20</admin_getAllocation_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getNotificationsPerDay();
        $this->assertEquals(20, $result);
    }

    public function testGetRequestsPerDay()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<admin_getAllocation_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">30</admin_getAllocation_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getRequestsPerDay();
        $this->assertEquals(30, $result);
    }

    public function testGetRestrictionInfo()
    {
        $this->markTestIncomplete();
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<admin_getRestrictionInfo_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"/>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getRestrictionInfo();
        $this->assertEquals('', $result);
    }
}

?>
