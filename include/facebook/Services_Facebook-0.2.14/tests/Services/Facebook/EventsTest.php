<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_EventsTest extends Services_Facebook_UnitTestCommon
{

    public function testGet()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<events_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <event>
    <eid>38696247746</eid>
    <name>The Good, The Bad and the Ugliest Christmas Sweater Party!</name>
    <tagline>Matt's turning 80!</tagline>
    <nid>0</nid>
    <pic>http://profile.ak.facebook.com/object3/1880/8/s38696247746_3895.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1880/8/n38696247746_3895.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1880/8/t38696247746_3895.jpg</pic_small>
    <host>DINOSAURS!</host>
    <description>It is matt's birthday on Friday and we are going to have a nice little shindig to celebrate. So come on down and dip your tits in Egg Nog!

Please wear Christmas/Holiday attire.  Especially those terrible xmas sweaters that you secretly wear in the dark.  

Bring your own booze please!</description>
    <event_type>Party</event_type>
    <event_subtype>Holiday Party</event_subtype>
    <start_time>1229140800</start_time>
    <end_time>1229155200</end_time>
    <creator>1058400102</creator>
    <update_time>1228881936</update_time>
    <location>JURASSIC PARK</location>
    <venue>
      <street>1337 Post Street Apt 600</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
    <hide_guest_list>0</hide_guest_list>
  </event>
</events_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->get(array('uid' => 231312));
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('event', $result);
        $this->assertObjectHasAttribute('eid', $result->event);
 
    }

    public function testGetEvents()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<events_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <event>
    <eid>38696247746</eid>
    <name>The Good, The Bad and the Ugliest Christmas Sweater Party!</name>
    <tagline>Matt's turning 80!</tagline>
    <nid>0</nid>
    <pic>http://profile.ak.facebook.com/object3/1880/8/s38696247746_3895.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1880/8/n38696247746_3895.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1880/8/t38696247746_3895.jpg</pic_small>
    <host>DINOSAURS!</host>
    <description>It is matt's birthday on Friday and we are going to have a nice little shindig to celebrate. So come on down and dip your tits in Egg Nog!

Please wear Christmas/Holiday attire.  Especially those terrible xmas sweaters that you secretly wear in the dark.  

Bring your own booze please!</description>
    <event_type>Party</event_type>
    <event_subtype>Holiday Party</event_subtype>
    <start_time>1229140800</start_time>
    <end_time>1229155200</end_time>
    <creator>1058400102</creator>
    <update_time>1228881936</update_time>
    <location>JURASSIC PARK</location>
    <venue>
      <street>1337 Post Street Apt 600</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
    <hide_guest_list>0</hide_guest_list>
  </event>
</events_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getEvents(231312);
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('event', $result);
        $this->assertObjectHasAttribute('eid', $result->event);
 
    }

    public function testGetEventsByUser()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<events_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <event>
    <eid>38696247746</eid>
    <name>The Good, The Bad and the Ugliest Christmas Sweater Party!</name>
    <tagline>Matt's turning 80!</tagline>
    <nid>0</nid>
    <pic>http://profile.ak.facebook.com/object3/1880/8/s38696247746_3895.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1880/8/n38696247746_3895.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1880/8/t38696247746_3895.jpg</pic_small>
    <host>DINOSAURS!</host>
    <description>It is matt's birthday on Friday and we are going to have a nice little shindig to celebrate. So come on down and dip your tits in Egg Nog!

Please wear Christmas/Holiday attire.  Especially those terrible xmas sweaters that you secretly wear in the dark.  

Bring your own booze please!</description>
    <event_type>Party</event_type>
    <event_subtype>Holiday Party</event_subtype>
    <start_time>1229140800</start_time>
    <end_time>1229155200</end_time>
    <creator>1058400102</creator>
    <update_time>1228881936</update_time>
    <location>JURASSIC PARK</location>
    <venue>
      <street>1337 Post Street Apt 600</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
    <hide_guest_list>0</hide_guest_list>
  </event>
</events_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getEventsByUser(231312);
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('event', $result);
        $this->assertObjectHasAttribute('eid', $result->event);
 
    }

    public function testGetEventsByDate()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<events_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <event>
    <eid>38696247746</eid>
    <name>The Good, The Bad and the Ugliest Christmas Sweater Party!</name>
    <tagline>Matt's turning 80!</tagline>
    <nid>0</nid>
    <pic>http://profile.ak.facebook.com/object3/1880/8/s38696247746_3895.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1880/8/n38696247746_3895.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1880/8/t38696247746_3895.jpg</pic_small>
    <host>DINOSAURS!</host>
    <description>It is matt's birthday on Friday and we are going to have a nice little shindig to celebrate. So come on down and dip your tits in Egg Nog!

Please wear Christmas/Holiday attire.  Especially those terrible xmas sweaters that you secretly wear in the dark.  

Bring your own booze please!</description>
    <event_type>Party</event_type>
    <event_subtype>Holiday Party</event_subtype>
    <start_time>1229140800</start_time>
    <end_time>1229155200</end_time>
    <creator>1058400102</creator>
    <update_time>1228881936</update_time>
    <location>JURASSIC PARK</location>
    <venue>
      <street>1337 Post Street Apt 600</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
    <hide_guest_list>0</hide_guest_list>
  </event>
</events_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getEventsByUser(time(), time() + 60 * 60 * 24 * 7);
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('event', $result);
        $this->assertObjectHasAttribute('eid', $result->event);
 
    }

    public function testGetMembers()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<events_getMembers_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">
  <attending list="true">
    <uid>132501701</uid>
    <uid>101400421</uid>
    <uid>6205900</uid>
    <uid>683416224</uid>
    <uid>1016847141</uid>
    <uid>28000695</uid>
    <uid>30509966</uid>
    <uid>666117457</uid>
    <uid>1303623749</uid>
    <uid>507394162</uid>
    <uid>64201001</uid>
    <uid>1027184425</uid>
    <uid>683226814</uid>
    <uid>1058400102</uid>
  </attending>
  <unsure list="true">
    <uid>545895519</uid>
    <uid>1463294160</uid>
    <uid>1112260748</uid>
    <uid>502299309</uid>
    <uid>527447742</uid>
    <uid>1058400113</uid>
    <uid>500793077</uid>
    <uid>572259524</uid>
    <uid>1058400105</uid>
    <uid>22700893</uid>
  </unsure>
  <declined list="true">
    <uid>5136658</uid>
    <uid>1036937674</uid>
    <uid>1277206209</uid>
    <uid>506788940</uid>
    <uid>794910098</uid>
    <uid>583639127</uid>
    <uid>64202981</uid>
    <uid>6417691</uid>
    <uid>60709424</uid>
    <uid>1084260891</uid>
    <uid>619377343</uid>
    <uid>1243083</uid>
    <uid>500550877</uid>
    <uid>1057500076</uid>
    <uid>33410299</uid>
    <uid>10805374</uid>
    <uid>501273039</uid>
    <uid>21000842</uid>
  </declined>
  <not_replied list="true">
    <uid>677045005</uid>
    <uid>773010382</uid>
    <uid>665867482</uid>
    <uid>641116400</uid>
    <uid>592636380</uid>
    <uid>603577553</uid>
    <uid>924343</uid>
    <uid>507903603</uid>
    <uid>1237591</uid>
    <uid>10805407</uid>
    <uid>759430170</uid>
    <uid>596010545</uid>
    <uid>560442481</uid>
    <uid>505221296</uid>
    <uid>1049580408</uid>
    <uid>645694847</uid>
    <uid>632211945</uid>
    <uid>655048618</uid>
    <uid>589156535</uid>
    <uid>653293902</uid>
    <uid>603678730</uid>
    <uid>690144881</uid>
    <uid>11707588</uid>
    <uid>753056286</uid>
    <uid>1244592948</uid>
    <uid>1163830726</uid>
    <uid>835268629</uid>
    <uid>25811874</uid>
    <uid>1416510010</uid>
    <uid>603778194</uid>
    <uid>580465429</uid>
    <uid>514681176</uid>
  </not_replied>
</events_getMembers_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getMembers(123213);
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('attending', $result);
        $this->assertObjectHasAttribute('unsure', $result);
        $this->assertObjectHasAttribute('declined', $result);
        $this->assertObjectHasAttribute('not_replied', $result);
 
    }
}

?>
