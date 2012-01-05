<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_PhotosTest extends Services_Facebook_UnitTestCommon
{

    public function testAddTag()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <photos_addTag_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  1 </photos_addTag_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->addTag(123, 33, 22, 424234);
        $this->assertTrue($result);
    }

    public function testAddTags()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <photos_addTag_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  1 </photos_addTag_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $args = array(
            'x' => 22,
            'y' => 33,
            'tag_uid' => 123123
        );
        $this->assertTrue($this->instance->addTags(123123, $args));
    }

    public function testCreateAlbum()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_createAlbum_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">
  <aid>2934436821880372235</aid>
  <cover_pid>0</cover_pid>
  <owner>683226814</owner>
  <name>Services Facebook yay!</name>
  <created>1232136504</created>
  <modified>1232136504</modified>
  <description/>
  <location/>
  <link>http://www.facebook.com/album.php?aid=97291&amp;id=683226814</link>
  <size>0</size>
  <visible>everyone</visible>
</photos_createAlbum_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';

        $result = $this->instance->createAlbum('Name', 'SF', 'w00t');
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('aid', $result);
    }

    public function testGetPhotos()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <photo>
    <pid>28834529265634446</pid>
    <aid>28834529231855401</aid>
    <owner>6713562</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/s6713562_35966094_2094.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/n6713562_35966094_2094.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/t6713562_35966094_2094.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35966094&amp;id=6713562</link>
    <caption/>
    <created>1231283786</created>
    <modified>1231284149</modified>
  </photo>
  <photo>
    <pid>2651587902313219917</pid>
    <aid>2651587902311568352</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/s617370918_1722189_264.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/n617370918_1722189_264.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/t617370918_1722189_264.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1722189&amp;id=617370918</link>
    <caption/>
    <created>1230972500</created>
    <modified>1230972712</modified>
  </photo>
  <photo>
    <pid>2651587902313137424</pid>
    <aid>2651587902311564978</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/s617370918_1639696_8916.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/n617370918_1639696_8916.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/t617370918_1639696_8916.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1639696&amp;id=617370918</link>
    <caption/>
    <created>1229461938</created>
    <modified>1229461939</modified>
  </photo>
  <photo>
    <pid>2651587902313082591</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584863_2520.jpg</src>
    <src_big>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584863_2520.jpg</src_big>
    <src_small>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584863_2520.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584863&amp;id=617370918</link>
    <caption>Kuma...still  my favorite dog!</caption>
    <created>1228166852</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313082590</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584862_641.jpg</src>
    <src_big>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584862_641.jpg</src_big>
    <src_small>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584862_641.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584862&amp;id=617370918</link>
    <caption>you can see how happy my brother an i were to wear life jackets!</caption>
    <created>1228166840</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024520</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/s617370918_1526792_7118.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/n617370918_1526792_7118.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/t617370918_1526792_7118.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526792&amp;id=617370918</link>
    <caption/>
    <created>1226866617</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024509</pid>
    <aid>2651587902311560306</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/s617370918_1526781_3883.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/n617370918_1526781_3883.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/t617370918_1526781_3883.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526781&amp;id=617370918</link>
    <caption/>
    <created>1226866201</created>
    <modified>1226866201</modified>
  </photo>
  <photo>
    <pid>14683449546160069</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188997_1317.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188997_1317.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188997_1317.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188997&amp;id=3418757</link>
    <caption/>
    <created>1213843131</created>
    <modified>1213843131</modified>
  </photo>
  <photo>
    <pid>14683449546160068</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188996_9508.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188996_9508.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188996_9508.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188996&amp;id=3418757</link>
    <caption/>
    <created>1213843129</created>
    <modified>1213843129</modified>
  </photo>
  <photo>
    <pid>14683449546159932</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188860_3141.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188860_3141.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188860_3141.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188860&amp;id=3418757</link>
    <caption/>
    <created>1213842343</created>
    <modified>1213842343</modified>
  </photo>
  <photo>
    <pid>14683449546159931</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188859_8642.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188859_8642.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188859_8642.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188859&amp;id=3418757</link>
    <caption/>
    <created>1213842288</created>
    <modified>1213842288</modified>
  </photo>
  <photo>
    <pid>14683449546159943</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188871_6079.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188871_6079.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188871_6079.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188871&amp;id=3418757</link>
    <caption/>
    <created>1213842406</created>
    <modified>1213842406</modified>
  </photo>
  <photo>
    <pid>14683449546159944</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188872_8587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188872_8587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188872_8587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188872&amp;id=3418757</link>
    <caption/>
    <created>1213842408</created>
    <modified>1213842408</modified>
  </photo>
  <photo>
    <pid>14683449546159800</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188728_3587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188728_3587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188728_3587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188728&amp;id=3418757</link>
    <caption/>
    <created>1213841233</created>
    <modified>1213841233</modified>
  </photo>
  <photo>
    <pid>14683449546159798</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188726_4195.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188726_4195.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188726_4195.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188726&amp;id=3418757</link>
    <caption/>
    <created>1213841184</created>
    <modified>1213841184</modified>
  </photo>
  <photo>
    <pid>2651587902312365772</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868044_8478.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868044_8478.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868044_8478.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868044&amp;id=617370918</link>
    <caption>ken and denis</caption>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312365773</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868045_8799.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868045_8799.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868045_8799.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868045&amp;id=617370918</link>
    <caption/>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312178114</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680386_3224.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680386_3224.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680386_3224.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680386&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178113</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680385_1972.jpg</src>
    <src_big>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680385_1972.jpg</src_big>
    <src_small>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680385_1972.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680385&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178111</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680383_664.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680383_664.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680383_664.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680383&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178110</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680382_22.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680382_22.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680382_22.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680382&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178109</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680381_9560.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680381_9560.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680381_9560.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680381&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178072</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680344_6594.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680344_6594.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680344_6594.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680344&amp;id=617370918</link>
    <caption/>
    <created>1204489032</created>
    <modified>1204489032</modified>
  </photo>
  <photo>
    <pid>2651587902311979531</pid>
    <aid>2651587902311519733</aid>
    <owner>617370918</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/s617370918_481803_9683.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/n617370918_481803_9683.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/t617370918_481803_9683.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=481803&amp;id=617370918</link>
    <caption/>
    <created>1196573168</created>
    <modified>1196573168</modified>
  </photo>
  <photo>
    <pid>2651587902311802216</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304488_445.jpg</src>
    <src_big>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304488_445.jpg</src_big>
    <src_small>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304488_445.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304488&amp;id=617370918</link>
    <caption>venice beach</caption>
    <created>1189385580</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902311802217</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304489_3449.jpg</src>
    <src_big>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304489_3449.jpg</src_big>
    <src_small>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304489_3449.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304489&amp;id=617370918</link>
    <caption/>
    <created>1189385593</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>14683449543738650</pid>
    <aid>14683449510151927</aid>
    <owner>3418757</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/s3418757_35767578_19.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/n3418757_35767578_19.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/t3418757_35767578_19.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35767578&amp;id=3418757</link>
    <caption/>
    <created>1193631539</created>
    <modified>1193631539</modified>
  </photo>
  <photo>
    <pid>3703144304673843188</pid>
    <aid>3703144304672309579</aid>
    <owner>862205472</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/s862205472_1599476_6377.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/n862205472_1599476_6377.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/t862205472_1599476_6377.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1599476&amp;id=862205472</link>
    <caption/>
    <created>1193605716</created>
    <modified>1228112482</modified>
  </photo>
</photos_get_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getPhotos(array(617370918, 123));
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result->photo as $photo) {
            $this->assertObjectHasAttribute('pid', $photo);
        }
    }

    public function testGetPhotosByAlbum()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <photo>
    <pid>28834529265634446</pid>
    <aid>28834529231855401</aid>
    <owner>6713562</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/s6713562_35966094_2094.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/n6713562_35966094_2094.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/t6713562_35966094_2094.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35966094&amp;id=6713562</link>
    <caption/>
    <created>1231283786</created>
    <modified>1231284149</modified>
  </photo>
  <photo>
    <pid>2651587902313219917</pid>
    <aid>2651587902311568352</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/s617370918_1722189_264.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/n617370918_1722189_264.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/t617370918_1722189_264.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1722189&amp;id=617370918</link>
    <caption/>
    <created>1230972500</created>
    <modified>1230972712</modified>
  </photo>
  <photo>
    <pid>2651587902313137424</pid>
    <aid>2651587902311564978</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/s617370918_1639696_8916.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/n617370918_1639696_8916.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/t617370918_1639696_8916.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1639696&amp;id=617370918</link>
    <caption/>
    <created>1229461938</created>
    <modified>1229461939</modified>
  </photo>
  <photo>
    <pid>2651587902313082591</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584863_2520.jpg</src>
    <src_big>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584863_2520.jpg</src_big>
    <src_small>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584863_2520.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584863&amp;id=617370918</link>
    <caption>Kuma...still  my favorite dog!</caption>
    <created>1228166852</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313082590</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584862_641.jpg</src>
    <src_big>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584862_641.jpg</src_big>
    <src_small>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584862_641.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584862&amp;id=617370918</link>
    <caption>you can see how happy my brother an i were to wear life jackets!</caption>
    <created>1228166840</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024520</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/s617370918_1526792_7118.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/n617370918_1526792_7118.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/t617370918_1526792_7118.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526792&amp;id=617370918</link>
    <caption/>
    <created>1226866617</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024509</pid>
    <aid>2651587902311560306</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/s617370918_1526781_3883.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/n617370918_1526781_3883.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/t617370918_1526781_3883.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526781&amp;id=617370918</link>
    <caption/>
    <created>1226866201</created>
    <modified>1226866201</modified>
  </photo>
  <photo>
    <pid>14683449546160069</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188997_1317.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188997_1317.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188997_1317.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188997&amp;id=3418757</link>
    <caption/>
    <created>1213843131</created>
    <modified>1213843131</modified>
  </photo>
  <photo>
    <pid>14683449546160068</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188996_9508.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188996_9508.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188996_9508.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188996&amp;id=3418757</link>
    <caption/>
    <created>1213843129</created>
    <modified>1213843129</modified>
  </photo>
  <photo>
    <pid>14683449546159932</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188860_3141.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188860_3141.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188860_3141.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188860&amp;id=3418757</link>
    <caption/>
    <created>1213842343</created>
    <modified>1213842343</modified>
  </photo>
  <photo>
    <pid>14683449546159931</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188859_8642.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188859_8642.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188859_8642.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188859&amp;id=3418757</link>
    <caption/>
    <created>1213842288</created>
    <modified>1213842288</modified>
  </photo>
  <photo>
    <pid>14683449546159943</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188871_6079.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188871_6079.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188871_6079.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188871&amp;id=3418757</link>
    <caption/>
    <created>1213842406</created>
    <modified>1213842406</modified>
  </photo>
  <photo>
    <pid>14683449546159944</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188872_8587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188872_8587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188872_8587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188872&amp;id=3418757</link>
    <caption/>
    <created>1213842408</created>
    <modified>1213842408</modified>
  </photo>
  <photo>
    <pid>14683449546159800</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188728_3587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188728_3587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188728_3587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188728&amp;id=3418757</link>
    <caption/>
    <created>1213841233</created>
    <modified>1213841233</modified>
  </photo>
  <photo>
    <pid>14683449546159798</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188726_4195.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188726_4195.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188726_4195.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188726&amp;id=3418757</link>
    <caption/>
    <created>1213841184</created>
    <modified>1213841184</modified>
  </photo>
  <photo>
    <pid>2651587902312365772</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868044_8478.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868044_8478.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868044_8478.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868044&amp;id=617370918</link>
    <caption>ken and denis</caption>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312365773</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868045_8799.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868045_8799.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868045_8799.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868045&amp;id=617370918</link>
    <caption/>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312178114</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680386_3224.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680386_3224.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680386_3224.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680386&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178113</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680385_1972.jpg</src>
    <src_big>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680385_1972.jpg</src_big>
    <src_small>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680385_1972.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680385&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178111</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680383_664.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680383_664.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680383_664.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680383&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178110</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680382_22.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680382_22.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680382_22.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680382&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178109</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680381_9560.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680381_9560.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680381_9560.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680381&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178072</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680344_6594.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680344_6594.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680344_6594.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680344&amp;id=617370918</link>
    <caption/>
    <created>1204489032</created>
    <modified>1204489032</modified>
  </photo>
  <photo>
    <pid>2651587902311979531</pid>
    <aid>2651587902311519733</aid>
    <owner>617370918</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/s617370918_481803_9683.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/n617370918_481803_9683.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/t617370918_481803_9683.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=481803&amp;id=617370918</link>
    <caption/>
    <created>1196573168</created>
    <modified>1196573168</modified>
  </photo>
  <photo>
    <pid>2651587902311802216</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304488_445.jpg</src>
    <src_big>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304488_445.jpg</src_big>
    <src_small>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304488_445.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304488&amp;id=617370918</link>
    <caption>venice beach</caption>
    <created>1189385580</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902311802217</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304489_3449.jpg</src>
    <src_big>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304489_3449.jpg</src_big>
    <src_small>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304489_3449.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304489&amp;id=617370918</link>
    <caption/>
    <created>1189385593</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>14683449543738650</pid>
    <aid>14683449510151927</aid>
    <owner>3418757</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/s3418757_35767578_19.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/n3418757_35767578_19.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/t3418757_35767578_19.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35767578&amp;id=3418757</link>
    <caption/>
    <created>1193631539</created>
    <modified>1193631539</modified>
  </photo>
  <photo>
    <pid>3703144304673843188</pid>
    <aid>3703144304672309579</aid>
    <owner>862205472</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/s862205472_1599476_6377.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/n862205472_1599476_6377.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/t862205472_1599476_6377.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1599476&amp;id=862205472</link>
    <caption/>
    <created>1193605716</created>
    <modified>1228112482</modified>
  </photo>
</photos_get_response>
XML;
        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getPhotosByAlbum(617370918);
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result->photo as $photo) {
            $this->assertObjectHasAttribute('pid', $photo);
        }
    }

    public function testGetPhotosByUser()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <photo>
    <pid>28834529265634446</pid>
    <aid>28834529231855401</aid>
    <owner>6713562</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/s6713562_35966094_2094.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/n6713562_35966094_2094.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-snc1/v1957/120/51/6713562/t6713562_35966094_2094.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35966094&amp;id=6713562</link>
    <caption/>
    <created>1231283786</created>
    <modified>1231284149</modified>
  </photo>
  <photo>
    <pid>2651587902313219917</pid>
    <aid>2651587902311568352</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/s617370918_1722189_264.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/n617370918_1722189_264.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v652/182/94/617370918/t617370918_1722189_264.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1722189&amp;id=617370918</link>
    <caption/>
    <created>1230972500</created>
    <modified>1230972712</modified>
  </photo>
  <photo>
    <pid>2651587902313137424</pid>
    <aid>2651587902311564978</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/s617370918_1639696_8916.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/n617370918_1639696_8916.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-snc1/v1837/182/94/617370918/t617370918_1639696_8916.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1639696&amp;id=617370918</link>
    <caption/>
    <created>1229461938</created>
    <modified>1229461939</modified>
  </photo>
  <photo>
    <pid>2651587902313082591</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584863_2520.jpg</src>
    <src_big>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584863_2520.jpg</src_big>
    <src_small>http://photos-h.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584863_2520.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584863&amp;id=617370918</link>
    <caption>Kuma...still  my favorite dog!</caption>
    <created>1228166852</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313082590</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/s617370918_1584862_641.jpg</src>
    <src_big>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/n617370918_1584862_641.jpg</src_big>
    <src_small>http://photos-g.pe.facebook.com/photos-pe-snc1/v972/182/94/617370918/t617370918_1584862_641.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1584862&amp;id=617370918</link>
    <caption>you can see how happy my brother an i were to wear life jackets!</caption>
    <created>1228166840</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024520</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/s617370918_1526792_7118.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/n617370918_1526792_7118.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v359/182/94/617370918/t617370918_1526792_7118.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526792&amp;id=617370918</link>
    <caption/>
    <created>1226866617</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902313024509</pid>
    <aid>2651587902311560306</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/s617370918_1526781_3883.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/n617370918_1526781_3883.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-snc1/v373/182/94/617370918/t617370918_1526781_3883.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1526781&amp;id=617370918</link>
    <caption/>
    <created>1226866201</created>
    <modified>1226866201</modified>
  </photo>
  <photo>
    <pid>14683449546160069</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188997_1317.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188997_1317.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188997_1317.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188997&amp;id=3418757</link>
    <caption/>
    <created>1213843131</created>
    <modified>1213843131</modified>
  </photo>
  <photo>
    <pid>14683449546160068</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188996_9508.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188996_9508.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188996_9508.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188996&amp;id=3418757</link>
    <caption/>
    <created>1213843129</created>
    <modified>1213843129</modified>
  </photo>
  <photo>
    <pid>14683449546159932</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188860_3141.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188860_3141.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188860_3141.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188860&amp;id=3418757</link>
    <caption/>
    <created>1213842343</created>
    <modified>1213842343</modified>
  </photo>
  <photo>
    <pid>14683449546159931</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188859_8642.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188859_8642.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188859_8642.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188859&amp;id=3418757</link>
    <caption/>
    <created>1213842288</created>
    <modified>1213842288</modified>
  </photo>
  <photo>
    <pid>14683449546159943</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188871_6079.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188871_6079.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188871_6079.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188871&amp;id=3418757</link>
    <caption/>
    <created>1213842406</created>
    <modified>1213842406</modified>
  </photo>
  <photo>
    <pid>14683449546159944</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188872_8587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188872_8587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188872_8587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188872&amp;id=3418757</link>
    <caption/>
    <created>1213842408</created>
    <modified>1213842408</modified>
  </photo>
  <photo>
    <pid>14683449546159800</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188728_3587.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188728_3587.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188728_3587.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188728&amp;id=3418757</link>
    <caption/>
    <created>1213841233</created>
    <modified>1213841233</modified>
  </photo>
  <photo>
    <pid>14683449546159798</pid>
    <aid>14683449510218628</aid>
    <owner>3418757</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/s3418757_38188726_4195.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/n3418757_38188726_4195.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v249/182/24/3418757/t3418757_38188726_4195.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=38188726&amp;id=3418757</link>
    <caption/>
    <created>1213841184</created>
    <modified>1213841184</modified>
  </photo>
  <photo>
    <pid>2651587902312365772</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868044_8478.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868044_8478.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868044_8478.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868044&amp;id=617370918</link>
    <caption>ken and denis</caption>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312365773</pid>
    <aid>2651587902311532808</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/s617370918_868045_8799.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/n617370918_868045_8799.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v241/182/94/617370918/t617370918_868045_8799.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=868045&amp;id=617370918</link>
    <caption/>
    <created>1211140268</created>
    <modified>1211140268</modified>
  </photo>
  <photo>
    <pid>2651587902312178114</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680386_3224.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680386_3224.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680386_3224.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680386&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178113</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680385_1972.jpg</src>
    <src_big>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680385_1972.jpg</src_big>
    <src_small>http://photos-b.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680385_1972.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680385&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178111</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680383_664.jpg</src>
    <src_big>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680383_664.jpg</src_big>
    <src_small>http://photos-h.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680383_664.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680383&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178110</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680382_22.jpg</src>
    <src_big>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680382_22.jpg</src_big>
    <src_small>http://photos-g.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680382_22.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680382&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178109</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680381_9560.jpg</src>
    <src_big>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680381_9560.jpg</src_big>
    <src_small>http://photos-f.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680381_9560.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680381&amp;id=617370918</link>
    <caption/>
    <created>1204489739</created>
    <modified>1204489739</modified>
  </photo>
  <photo>
    <pid>2651587902312178072</pid>
    <aid>2651587902311527698</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/s617370918_680344_6594.jpg</src>
    <src_big>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/n617370918_680344_6594.jpg</src_big>
    <src_small>http://photos-a.ak.fbcdn.net/photos-ak-sf2p/v200/182/94/617370918/t617370918_680344_6594.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=680344&amp;id=617370918</link>
    <caption/>
    <created>1204489032</created>
    <modified>1204489032</modified>
  </photo>
  <photo>
    <pid>2651587902311979531</pid>
    <aid>2651587902311519733</aid>
    <owner>617370918</owner>
    <src>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/s617370918_481803_9683.jpg</src>
    <src_big>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/n617370918_481803_9683.jpg</src_big>
    <src_small>http://photos-d.ak.fbcdn.net/photos-ak-sf2p/v166/182/94/617370918/t617370918_481803_9683.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=481803&amp;id=617370918</link>
    <caption/>
    <created>1196573168</created>
    <modified>1196573168</modified>
  </photo>
  <photo>
    <pid>2651587902311802216</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304488_445.jpg</src>
    <src_big>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304488_445.jpg</src_big>
    <src_small>http://photos-a.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304488_445.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304488&amp;id=617370918</link>
    <caption>venice beach</caption>
    <created>1189385580</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>2651587902311802217</pid>
    <aid>2651587906606465021</aid>
    <owner>617370918</owner>
    <src>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/s617370918_304489_3449.jpg</src>
    <src_big>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/n617370918_304489_3449.jpg</src_big>
    <src_small>http://photos-b.ll.facebook.com/photos-ll-sf2p/v122/182/94/617370918/t617370918_304489_3449.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=304489&amp;id=617370918</link>
    <caption/>
    <created>1189385593</created>
    <modified>1229048382</modified>
  </photo>
  <photo>
    <pid>14683449543738650</pid>
    <aid>14683449510151927</aid>
    <owner>3418757</owner>
    <src>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/s3418757_35767578_19.jpg</src>
    <src_big>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/n3418757_35767578_19.jpg</src_big>
    <src_small>http://photos-c.ak.fbcdn.net/photos-ak-sf2p/v150/182/24/3418757/t3418757_35767578_19.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=35767578&amp;id=3418757</link>
    <caption/>
    <created>1193631539</created>
    <modified>1193631539</modified>
  </photo>
  <photo>
    <pid>3703144304673843188</pid>
    <aid>3703144304672309579</aid>
    <owner>862205472</owner>
    <src>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/s862205472_1599476_6377.jpg</src>
    <src_big>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/n862205472_1599476_6377.jpg</src_big>
    <src_small>http://photos-e.ak.fbcdn.net/photos-ak-sf2p/v142/82/5/862205472/t862205472_1599476_6377.jpg</src_small>
    <link>http://www.facebook.com/photo.php?pid=1599476&amp;id=862205472</link>
    <caption/>
    <created>1193605716</created>
    <modified>1228112482</modified>
  </photo>
</photos_get_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getPhotosByUser(617370918);
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result->photo as $photo) {
            $this->assertObjectHasAttribute('pid', $photo);
        }
    }

    public function testGetAlbumsByPhotos()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_getAlbums_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <album>
    <aid>2651587902311568352</aid>
    <cover_pid>2651587902313219917</cover_pid>
    <owner>617370918</owner>
    <name>estee and i</name>
    <created>1230972414</created>
    <modified>1230972618</modified>
    <description/>
    <location>newbury park</location>
    <link>http://www.facebook.com/album.php?aid=70624&amp;id=617370918</link>
    <size>7</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311564978</aid>
    <cover_pid>2651587902313137415</cover_pid>
    <owner>617370918</owner>
    <name>Snow in cupertino!</name>
    <created>1229461738</created>
    <modified>1229462035</modified>
    <description>Snow!!!!!</description>
    <location>cupertino</location>
    <link>http://www.facebook.com/album.php?aid=67250&amp;id=617370918</link>
    <size>19</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311560306</aid>
    <cover_pid>2651587902313024507</cover_pid>
    <owner>617370918</owner>
    <name>american?</name>
    <created>1226866121</created>
    <modified>1226866204</modified>
    <description/>
    <location/>
    <link>http://www.facebook.com/album.php?aid=62578&amp;id=617370918</link>
    <size>4</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311558084</aid>
    <cover_pid>2651587902312974711</cover_pid>
    <owner>617370918</owner>
    <name>i love rain weather!!!!</name>
    <created>1225751276</created>
    <modified>1225751984</modified>
    <description/>
    <location>malibu</location>
    <link>http://www.facebook.com/album.php?aid=60356&amp;id=617370918</link>
    <size>13</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311532808</aid>
    <cover_pid>2651587902312311042</cover_pid>
    <owner>617370918</owner>
    <name>Spring</name>
    <created>1209341769</created>
    <modified>1211429503</modified>
    <description/>
    <location>ucsc campus</location>
    <link>http://www.facebook.com/album.php?aid=35080&amp;id=617370918</link>
    <size>15</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311527698</aid>
    <cover_pid>2651587902312178061</cover_pid>
    <owner>617370918</owner>
    <name>Spring Beauty</name>
    <created>1204488933</created>
    <modified>1204489883</modified>
    <description/>
    <location>Santa Cruz</location>
    <link>http://www.facebook.com/album.php?aid=29970&amp;id=617370918</link>
    <size>33</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311526858</aid>
    <cover_pid>2651587902312155878</cover_pid>
    <owner>617370918</owner>
    <name>santa cruz</name>
    <created>1203654045</created>
    <modified>1204489844</modified>
    <description/>
    <location/>
    <link>http://www.facebook.com/album.php?aid=29130&amp;id=617370918</link>
    <size>8</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311519733</aid>
    <cover_pid>2651587902311979501</cover_pid>
    <owner>617370918</owner>
    <name>muddy wendy trails</name>
    <created>1196572694</created>
    <modified>1196573645</modified>
    <description/>
    <location>top of wendy</location>
    <link>http://www.facebook.com/album.php?aid=22005&amp;id=617370918</link>
    <size>12</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311517202</aid>
    <cover_pid>2651587902311922213</cover_pid>
    <owner>617370918</owner>
    <name>Amberger and kendo's trip on bikes to the mystery spot!</name>
    <created>1194239947</created>
    <modified>1194240131</modified>
    <description>it was an epic adventure that took quite some time</description>
    <location>Santa Cruz</location>
    <link>http://www.facebook.com/album.php?aid=19474&amp;id=617370918</link>
    <size>12</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311516931</aid>
    <cover_pid>2651587902311915715</cover_pid>
    <owner>617370918</owner>
    <name>estee's adventure in santa cruz</name>
    <created>1194039370</created>
    <modified>1194055954</modified>
    <description/>
    <location>forest</location>
    <link>http://www.facebook.com/album.php?aid=19203&amp;id=617370918</link>
    <size>7</size>
    <visible xsi:nil="true"/>
  </album>
</photos_getAlbums_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getAlbumsByPhotos(array(617370918));
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result->album as $album) {
            $this->assertObjectHasAttribute('aid', $album);
        }
    }

    public function testGetAlbumsByUser()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_getAlbums_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <album>
    <aid>2651587902311568352</aid>
    <cover_pid>2651587902313219917</cover_pid>
    <owner>617370918</owner>
    <name>estee and i</name>
    <created>1230972414</created>
    <modified>1230972618</modified>
    <description/>
    <location>newbury park</location>
    <link>http://www.facebook.com/album.php?aid=70624&amp;id=617370918</link>
    <size>7</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311564978</aid>
    <cover_pid>2651587902313137415</cover_pid>
    <owner>617370918</owner>
    <name>Snow in cupertino!</name>
    <created>1229461738</created>
    <modified>1229462035</modified>
    <description>Snow!!!!!</description>
    <location>cupertino</location>
    <link>http://www.facebook.com/album.php?aid=67250&amp;id=617370918</link>
    <size>19</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311560306</aid>
    <cover_pid>2651587902313024507</cover_pid>
    <owner>617370918</owner>
    <name>american?</name>
    <created>1226866121</created>
    <modified>1226866204</modified>
    <description/>
    <location/>
    <link>http://www.facebook.com/album.php?aid=62578&amp;id=617370918</link>
    <size>4</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311558084</aid>
    <cover_pid>2651587902312974711</cover_pid>
    <owner>617370918</owner>
    <name>i love rain weather!!!!</name>
    <created>1225751276</created>
    <modified>1225751984</modified>
    <description/>
    <location>malibu</location>
    <link>http://www.facebook.com/album.php?aid=60356&amp;id=617370918</link>
    <size>13</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311532808</aid>
    <cover_pid>2651587902312311042</cover_pid>
    <owner>617370918</owner>
    <name>Spring</name>
    <created>1209341769</created>
    <modified>1211429503</modified>
    <description/>
    <location>ucsc campus</location>
    <link>http://www.facebook.com/album.php?aid=35080&amp;id=617370918</link>
    <size>15</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311527698</aid>
    <cover_pid>2651587902312178061</cover_pid>
    <owner>617370918</owner>
    <name>Spring Beauty</name>
    <created>1204488933</created>
    <modified>1204489883</modified>
    <description/>
    <location>Santa Cruz</location>
    <link>http://www.facebook.com/album.php?aid=29970&amp;id=617370918</link>
    <size>33</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311526858</aid>
    <cover_pid>2651587902312155878</cover_pid>
    <owner>617370918</owner>
    <name>santa cruz</name>
    <created>1203654045</created>
    <modified>1204489844</modified>
    <description/>
    <location/>
    <link>http://www.facebook.com/album.php?aid=29130&amp;id=617370918</link>
    <size>8</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311519733</aid>
    <cover_pid>2651587902311979501</cover_pid>
    <owner>617370918</owner>
    <name>muddy wendy trails</name>
    <created>1196572694</created>
    <modified>1196573645</modified>
    <description/>
    <location>top of wendy</location>
    <link>http://www.facebook.com/album.php?aid=22005&amp;id=617370918</link>
    <size>12</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311517202</aid>
    <cover_pid>2651587902311922213</cover_pid>
    <owner>617370918</owner>
    <name>Amberger and kendo's trip on bikes to the mystery spot!</name>
    <created>1194239947</created>
    <modified>1194240131</modified>
    <description>it was an epic adventure that took quite some time</description>
    <location>Santa Cruz</location>
    <link>http://www.facebook.com/album.php?aid=19474&amp;id=617370918</link>
    <size>12</size>
    <visible xsi:nil="true"/>
  </album>
  <album>
    <aid>2651587902311516931</aid>
    <cover_pid>2651587902311915715</cover_pid>
    <owner>617370918</owner>
    <name>estee's adventure in santa cruz</name>
    <created>1194039370</created>
    <modified>1194055954</modified>
    <description/>
    <location>forest</location>
    <link>http://www.facebook.com/album.php?aid=19203&amp;id=617370918</link>
    <size>7</size>
    <visible xsi:nil="true"/>
  </album>
</photos_getAlbums_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getAlbumsByUser(617370918);
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result->album as $album) {
            $this->assertObjectHasAttribute('aid', $album);
        }
    }

    public function testGetTags()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<photos_getTags_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <photo_tag>
    <pid>28834529265634446</pid>
    <subject>617370918</subject>
    <text>Kendra Puckett</text>
    <xcoord>48.2759</xcoord>
    <ycoord>43.5644</ycoord>
    <created>1231287409</created>
  </photo_tag>
</photos_getTags_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->getTags(array('28834529265634446'));
        $this->assertType('SimpleXMLElement', $result);
        $this->assertObjectHasAttribute('photo_tag', $result);
        $this->assertObjectHasAttribute('text', $result->photo_tag);
    }

}

?>
