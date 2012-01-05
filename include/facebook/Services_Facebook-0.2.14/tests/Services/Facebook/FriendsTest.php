<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_FriendsTest extends Services_Facebook_UnitTestCommon
{

    public function testAreFriends()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<friends_areFriends_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <friend_info>
    <uid1>617370918</uid1>
    <uid2>683226814</uid2>
    <are_friends>1</are_friends>
  </friend_info>
</friends_areFriends_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->areFriends(617370918, 683226814);
        $this->assertTrue($result);
    }

    public function testGetByList()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<friends_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <uid>31504263</uid>
  <uid>718684161</uid>
</friends_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getByList(18229301814);
        $this->assertEquals(count($result), 2);
    }

    public function testGetAppUsers()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <friends_getAppUsers_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">  <uid>222333</uid>  <uid>1240079</uid> </friends_getAppUsers_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getAppUsers();
        $this->assertEquals(count($result), 2);
    }

    public function testGetLists()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<friends_getLists_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <friendlist>
    <flid>18229301814</flid>
    <name>OoTT</name>
  </friendlist>
  <friendlist>
    <flid>12251171814</flid>
    <name>Limited Profile</name>
  </friendlist>
  <friendlist>
    <flid>14200531814</flid>
    <name>Test2</name>
  </friendlist>
  <friendlist>
    <flid>14198496814</flid>
    <name>Test</name>
  </friendlist>
</friends_getLists_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = 'foobar';
        $result = $this->instance->getLists();
        $this->assertType('SimpleXMLElement', $result);
        $this->assertEquals(count($result), 4);
    }

    public function testAreFriendsMult()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<friends_areFriends_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <friend_info>
    <uid1>683226814</uid1>
    <uid2>3420251</uid2>
    <are_friends>0</are_friends>
  </friend_info>
  <friend_info>
    <uid1>617370918</uid1>
    <uid2>1337292651</uid2>
    <are_friends>1</are_friends>
  </friend_info>
</friends_areFriends_response>
XML;

        $this->mockSendRequest($response);
        $uid1 = array(683226814, 617370918);
        $uid2 = array(3420251, 1337292651);
        $result = $this->instance->areFriends($uid1, $uid2);
        $this->assertType('SimpleXMLElement', $result);
        for ($i = 0; $i < 2; $i++) {
            $this->assertTrue(($result->friend_info[$i]->are_friends == $i));
        }
    }

    public function testGet()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<friends_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <uid>924343</uid>
  <uid>1800610</uid>
  <uid>1801217</uid>
  <uid>1956303</uid>
  <uid>2047410</uid>
  <uid>2060613</uid>
  <uid>2339274</uid>
  <uid>2525874</uid>
  <uid>2900526</uid>
  <uid>3206887</uid>
  <uid>3211942</uid>
  <uid>3600830</uid>
  <uid>3615689</uid>
  <uid>5136658</uid>
  <uid>5213428</uid>
  <uid>5219119</uid>
  <uid>5502809</uid>
  <uid>5506555</uid>
  <uid>6205900</uid>
  <uid>6208191</uid>
  <uid>6419079</uid>
  <uid>6707823</uid>
  <uid>6709381</uid>
  <uid>7100591</uid>
  <uid>7300752</uid>
  <uid>7900976</uid>
  <uid>7907485</uid>
  <uid>10100090</uid>
  <uid>10100094</uid>
  <uid>10805374</uid>
  <uid>10805407</uid>
  <uid>11710089</uid>
  <uid>11710442</uid>
  <uid>11711995</uid>
  <uid>11712668</uid>
  <uid>11713628</uid>
  <uid>13901499</uid>
  <uid>16810426</uid>
  <uid>19101078</uid>
  <uid>24400215</uid>
  <uid>24604046</uid>
  <uid>25820054</uid>
  <uid>28000695</uid>
  <uid>31206956</uid>
  <uid>31504263</uid>
  <uid>31505226</uid>
  <uid>48500398</uid>
  <uid>139501608</uid>
  <uid>150800301</uid>
  <uid>205500751</uid>
  <uid>207600079</uid>
  <uid>300600986</uid>
  <uid>500014674</uid>
  <uid>500064183</uid>
  <uid>500550877</uid>
  <uid>501969281</uid>
  <uid>502299309</uid>
  <uid>502572405</uid>
  <uid>504036696</uid>
  <uid>505261656</uid>
  <uid>506530677</uid>
  <uid>506587162</uid>
  <uid>506788940</uid>
  <uid>507128476</uid>
  <uid>507394162</uid>
  <uid>507448344</uid>
  <uid>507448584</uid>
  <uid>507711850</uid>
  <uid>507903603</uid>
  <uid>508643272</uid>
  <uid>508783820</uid>
  <uid>510631642</uid>
  <uid>510895546</uid>
  <uid>511618270</uid>
  <uid>512633777</uid>
  <uid>515853053</uid>
  <uid>516321254</uid>
  <uid>516392300</uid>
  <uid>516757131</uid>
  <uid>516963717</uid>
  <uid>517935176</uid>
  <uid>518325749</uid>
  <uid>518481798</uid>
  <uid>520021154</uid>
  <uid>521083801</uid>
  <uid>521541519</uid>
  <uid>521701871</uid>
  <uid>523947141</uid>
  <uid>524762567</uid>
  <uid>525283734</uid>
  <uid>526312843</uid>
  <uid>527595441</uid>
  <uid>533169573</uid>
  <uid>533547433</uid>
  <uid>533871460</uid>
  <uid>536269226</uid>
  <uid>536293752</uid>
  <uid>536985977</uid>
  <uid>540337396</uid>
  <uid>540611343</uid>
  <uid>541263020</uid>
  <uid>541381164</uid>
  <uid>542846298</uid>
  <uid>543360467</uid>
  <uid>543695617</uid>
  <uid>544361673</uid>
  <uid>544916521</uid>
  <uid>547217860</uid>
  <uid>547304461</uid>
  <uid>549003324</uid>
  <uid>549025920</uid>
  <uid>550123083</uid>
  <uid>551623090</uid>
  <uid>553420695</uid>
  <uid>554268300</uid>
  <uid>555921469</uid>
  <uid>556391032</uid>
  <uid>557755726</uid>
  <uid>558470465</uid>
  <uid>561029031</uid>
  <uid>561413796</uid>
  <uid>565787064</uid>
  <uid>566771148</uid>
  <uid>567693799</uid>
  <uid>570936903</uid>
  <uid>571503332</uid>
  <uid>571573101</uid>
  <uid>571672353</uid>
  <uid>572062896</uid>
  <uid>572296682</uid>
  <uid>574255686</uid>
  <uid>576421097</uid>
  <uid>576828685</uid>
  <uid>576982991</uid>
  <uid>578822015</uid>
  <uid>579358722</uid>
  <uid>582326215</uid>
  <uid>586036296</uid>
  <uid>586843227</uid>
  <uid>587212468</uid>
  <uid>588183661</uid>
  <uid>590288834</uid>
  <uid>591775885</uid>
  <uid>592697827</uid>
  <uid>594312720</uid>
  <uid>595635887</uid>
  <uid>596289435</uid>
  <uid>596671190</uid>
  <uid>597511912</uid>
  <uid>597641690</uid>
  <uid>599565900</uid>
  <uid>600725188</uid>
  <uid>601212815</uid>
  <uid>602388611</uid>
  <uid>602493988</uid>
  <uid>602621859</uid>
  <uid>604358504</uid>
  <uid>604946655</uid>
  <uid>606911109</uid>
  <uid>607599534</uid>
  <uid>608113377</uid>
  <uid>608286842</uid>
  <uid>609211484</uid>
  <uid>610632906</uid>
  <uid>613065196</uid>
  <uid>615635031</uid>
  <uid>616511426</uid>
  <uid>617370918</uid>
  <uid>619208974</uid>
  <uid>620431575</uid>
  <uid>621926171</uid>
  <uid>625039006</uid>
  <uid>626288316</uid>
  <uid>627311631</uid>
  <uid>628843106</uid>
  <uid>629986493</uid>
  <uid>633297258</uid>
  <uid>634907956</uid>
  <uid>635266168</uid>
  <uid>639057390</uid>
  <uid>640061028</uid>
  <uid>641116400</uid>
  <uid>641212743</uid>
  <uid>641319101</uid>
  <uid>642015822</uid>
  <uid>643030443</uid>
  <uid>644430608</uid>
  <uid>646061993</uid>
  <uid>646593393</uid>
  <uid>649935359</uid>
  <uid>649958816</uid>
  <uid>651196926</uid>
  <uid>652965146</uid>
  <uid>654094921</uid>
  <uid>654876210</uid>
  <uid>655048618</uid>
  <uid>656446026</uid>
  <uid>658641336</uid>
  <uid>659382260</uid>
  <uid>659778406</uid>
  <uid>660524972</uid>
  <uid>661721333</uid>
  <uid>662764289</uid>
  <uid>663022026</uid>
  <uid>665613168</uid>
  <uid>666653204</uid>
  <uid>667337224</uid>
  <uid>667548457</uid>
  <uid>669245952</uid>
  <uid>670105209</uid>
  <uid>670956942</uid>
  <uid>672653037</uid>
  <uid>673235384</uid>
  <uid>675071752</uid>
  <uid>675745947</uid>
  <uid>679174934</uid>
  <uid>680481527</uid>
  <uid>680685997</uid>
  <uid>682871390</uid>
  <uid>683320891</uid>
  <uid>684531501</uid>
  <uid>686252440</uid>
  <uid>686532196</uid>
  <uid>687441766</uid>
  <uid>687707451</uid>
  <uid>687938316</uid>
  <uid>688987220</uid>
  <uid>689096433</uid>
  <uid>689828410</uid>
  <uid>690210392</uid>
  <uid>692190054</uid>
  <uid>692897180</uid>
  <uid>695499141</uid>
  <uid>695641750</uid>
  <uid>697048711</uid>
  <uid>697691163</uid>
  <uid>700774063</uid>
  <uid>702787551</uid>
  <uid>706008656</uid>
  <uid>706776770</uid>
  <uid>707751678</uid>
  <uid>708727040</uid>
  <uid>709132233</uid>
  <uid>709260942</uid>
  <uid>709320825</uid>
  <uid>710265293</uid>
  <uid>711915061</uid>
  <uid>713872353</uid>
  <uid>714455356</uid>
  <uid>715657454</uid>
  <uid>718109851</uid>
  <uid>718684161</uid>
  <uid>719576868</uid>
  <uid>720136749</uid>
  <uid>720549661</uid>
  <uid>721752198</uid>
  <uid>721775649</uid>
  <uid>722051392</uid>
  <uid>725366340</uid>
  <uid>726586304</uid>
  <uid>727590181</uid>
  <uid>728539606</uid>
  <uid>730537898</uid>
  <uid>732231862</uid>
  <uid>732395944</uid>
  <uid>735430450</uid>
  <uid>737856139</uid>
  <uid>739395134</uid>
  <uid>742661693</uid>
  <uid>745936519</uid>
  <uid>747192526</uid>
  <uid>751237848</uid>
  <uid>753940266</uid>
  <uid>760591799</uid>
  <uid>760796612</uid>
  <uid>762194630</uid>
  <uid>765728102</uid>
  <uid>777395203</uid>
  <uid>781260172</uid>
  <uid>784075172</uid>
  <uid>790668494</uid>
  <uid>791429352</uid>
  <uid>791879224</uid>
  <uid>795383134</uid>
  <uid>799223573</uid>
  <uid>802734193</uid>
  <uid>807849767</uid>
  <uid>812775243</uid>
  <uid>817463397</uid>
  <uid>818550430</uid>
  <uid>821147747</uid>
  <uid>830614423</uid>
  <uid>833555899</uid>
  <uid>833694201</uid>
  <uid>855010639</uid>
  <uid>898930299</uid>
  <uid>1030813535</uid>
  <uid>1031820366</uid>
  <uid>1041801699</uid>
  <uid>1042800033</uid>
  <uid>1047480003</uid>
  <uid>1047480027</uid>
  <uid>1047480028</uid>
  <uid>1047480047</uid>
  <uid>1047480049</uid>
  <uid>1047480056</uid>
  <uid>1047480068</uid>
  <uid>1047480078</uid>
  <uid>1047480082</uid>
  <uid>1047480083</uid>
  <uid>1047480094</uid>
  <uid>1047480099</uid>
  <uid>1047480138</uid>
  <uid>1047480140</uid>
  <uid>1047480162</uid>
  <uid>1047480169</uid>
  <uid>1047480175</uid>
  <uid>1047480185</uid>
  <uid>1047480188</uid>
  <uid>1048530026</uid>
  <uid>1049204751</uid>
  <uid>1058400102</uid>
  <uid>1058970042</uid>
  <uid>1060999433</uid>
  <uid>1064568546</uid>
  <uid>1065231417</uid>
  <uid>1068060088</uid>
  <uid>1074024060</uid>
  <uid>1093958202</uid>
  <uid>1100506869</uid>
  <uid>1105996949</uid>
  <uid>1108230137</uid>
  <uid>1122900356</uid>
  <uid>1131083147</uid>
  <uid>1191274762</uid>
  <uid>1201217781</uid>
  <uid>1209156204</uid>
  <uid>1212263224</uid>
  <uid>1225675163</uid>
  <uid>1244041233</uid>
  <uid>1259109563</uid>
  <uid>1262885018</uid>
  <uid>1302414215</uid>
  <uid>1303623749</uid>
  <uid>1306177216</uid>
  <uid>1306292746</uid>
  <uid>1319042930</uid>
  <uid>1327796747</uid>
  <uid>1398780274</uid>
  <uid>1407927100</uid>
  <uid>1415930588</uid>
  <uid>1427857604</uid>
  <uid>1455108770</uid>
  <uid>1477906749</uid>
  <uid>1491106587</uid>
  <uid>1534789171</uid>
  <uid>1548630129</uid>
  <uid>1549787383</uid>
  <uid>1556717803</uid>
  <uid>1642191667</uid>
</friends_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->get(683226814);
        $this->assertEquals(count($result), 360);
    }
}

?>
