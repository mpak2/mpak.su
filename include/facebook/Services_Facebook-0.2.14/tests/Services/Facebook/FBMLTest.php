<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_FBMLTest extends Services_Facebook_UnitTestCommon
{

    public function testRefreshImgSrc()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <fbml_refreshImgSrc_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  1 </fbml_refreshImgSrc_response>
XML;

        $this->mockSendRequest($response);
        $this->assertTrue($this->instance->refreshImgSrc('foo'));
 
    }

    public function testRefreshRefUrl()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <fbml_refreshRefUrl_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  1 </fbml_refreshRefUrl_response>
XML;

        $this->mockSendRequest($response);
        $this->assertTrue($this->instance->refreshRefUrl('foo'));
 
    }

    public function testSetRefHandle()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <fbml_setRefHandle_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  1 </fbml_setRefHandle_response>
XML;

        $this->mockSendRequest($response);
        $this->assertTrue($this->instance->setRefHandle('foo', 'bar'));
 
    }

}

?>
