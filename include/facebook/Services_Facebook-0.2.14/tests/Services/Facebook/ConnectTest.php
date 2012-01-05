<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_ConnectTest extends Services_Facebook_UnitTestCommon
{

    public function testGetUnconnectedFriendsCount()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<connect_getUnconnectedFriendsCount_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">0</connect_getUnconnectedFriendsCount_response>
XML;

        $this->mockSendRequest($response);
        $this->assertEquals(0, $this->instance->getUnconnectedFriendsCount());
    }

    public function testRegisterUsers()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<connect_registerUsers_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <connect_registerUsers_response_elt>3597213989_f5b8fb60c6116331da07c65b96a8a1d1</connect_registerUsers_response_elt>
    <connect_registerUsers_response_elt>1743195695_893629e8dd30a343323ffc92487432c5</connect_registerUsers_response_elt>
</connect_registerUsers_response>
XML;

        $this->mockSendRequest($response);

        $hash1 = Services_Facebook_Connect::hashEmail('joe@example.com');
        $hash2 = Services_Facebook_Connect::hashEmail('jeff@example.com');
        $args = array();
        $args[] = array(
            'email_hash'  => $hash1,
            'account_id'  => 12345678,
            'account_url' => 'http://example.com/users?id=12345678'
        );

        $args[] = array(
            'email_hash'  => $hash2
        );

        $result = $this->instance->registerUsers($args);

        $this->assertEquals('3597213989_f5b8fb60c6116331da07c65b96a8a1d1', $result[0]);
        $this->assertEquals('1743195695_893629e8dd30a343323ffc92487432c5', $result[1]);
    }

    /**
     * @expectedException Services_Facebook_Exception
     */
    public function testRegisterUsersEmptyEmail()
    {
        $args = array();
        $args[] = array(
            'account_id'  => 12345678,
            'account_url' => 'http://example.com/users?id=12345678'
        );

        $args[] = array(
            'account_id'  => 12348,
        );

        $result = $this->instance->registerUsers($args);
    }

    /**
     * @expectedException Services_Facebook_Exception
     */
    public function testRegisterUsersInvalidField()
    {
        $hash2 = Services_Facebook_Connect::hashEmail('jeff@example.com');
        $args = array();
        $args[] = array(
            'email_hash'  => $hash2,
            'accoasdfkkkunt_id'  => 12345678,
            'accouasdfnt_url' => 'http://example.com/users?id=12345678'
        );

        $args[] = array(
            'email_hash'  => $hash2,
            'acasdfcount_id'  => 12348
        );

        $result = $this->instance->registerUsers($args);
    }

    public function testUnregisterUsers()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<connect_unregisterUsers_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">1</connect_unregisterUsers_response>
XML;

        $this->mockSendRequest($response);

        $hash1 = Services_Facebook_Connect::hashEmail('joe@example.com');
        $hash2 = Services_Facebook_Connect::hashEmail('jeff@example.com');
        $args = array($hash1, $hash2);

        $result = $this->instance->unregisterUsers($args);

        $this->assertTrue($result);
    }

    /**
     * @expectedException Services_Facebook_Exception
     */
    public function testInvalidEmail()
    {
        $email = Services_Facebook_Connect::hashEmail('jeffexample.com');
    }

}

?>
