<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_AuthTest extends Services_Facebook_UnitTestCommon
{

    public function testCreateToken()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <auth_createToken_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/http://api.facebook.com/1.0/facebook.xsd">3e4a22bb2f5ed75114b0fc9995ea85f1</auth_createToken_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->createToken();
        $this->assertEquals('3e4a22bb2f5ed75114b0fc9995ea85f1', $result);
    }

    public function testGetSession()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <auth_getSession_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  <session_key>5f34e11bfb97c762e439e6a5-8055</session_key>  <uid>8055</uid>  <expires>1173309298</expires> </auth_getSession_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getSession('asdfsdf123123');
        $this->assertEquals('5f34e11bfb97c762e439e6a5-8055', (string) $result->session_key);
        $this->assertEquals('8055', (string) $result->uid);
        $this->assertEquals('1173309298', (string) $result->expires);
    }

    public function testPromoteSession()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <auth_promoteSession_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/http://api.facebook.com/1.0/facebook.xsd">3e4a22bb2f5ed75114b0fc9995ea85f1</auth_promoteSession_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->promoteSession();
        $this->assertEquals('3e4a22bb2f5ed75114b0fc9995ea85f1', $result);

    }

    public function testExpireSession()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <auth_expireSession_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/http://api.facebook.com/1.0/facebook.xsd">1</auth_expireSession_response>
XML;

        $this->mockSendRequest($response);
        $this->assertTrue($this->instance->expireSession());
    }

    public function testRevokeAuthorization()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <auth_revokeAuthorization_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/http://api.facebook.com/1.0/facebook.xsd">1</auth_revokeAuthorization_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = 'foo';
        $result = $this->instance->revokeAuthorization();
        $this->assertTrue($result);

        $this->instance->sessionKey = null;
        $result = $this->instance->revokeAuthorization(123);
        $this->assertTrue($result);
    }

}

?>
