<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_PagesTest extends Services_Facebook_UnitTestCommon
{

    public function testIsAdmin()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<pages_isAdmin_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">0</pages_isAdmin_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $this->assertFalse($this->instance->isAdmin(123));
    }

    public function testIsAppAdded()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <pages_isAppAdded_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</pages_isAppAdded_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $this->assertTrue($this->instance->isAppAdded(123));
    }

    public function testIsFan()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<pages_isFan_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">0</pages_isFan_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $this->assertFalse($this->instance->isFan(123, 12333));
    }

}

?>
