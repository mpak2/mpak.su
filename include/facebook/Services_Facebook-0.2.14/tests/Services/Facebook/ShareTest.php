<?php

require_once 'UnitTestCommon.php';
require_once 'Services/Facebook/Share.php';

class Services_Facebook_ShareTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $html = <<<XML
<fb:share-button class="meta"> <meta name="medium" content="blog"/> <meta name="title" content="Leonidas in All of Us"/> <meta name="video_type" content="application/x-shockwave-flash"/> <meta name="video_height" content="345"/> <meta name="video_width" content="473"/> <meta name="description" content="That's the lesson 300 teaches us."/> <link rel="image_src" href="http://9.content.collegehumor.com/d1/ch6/f/6/collegehumor.b38e345f621621dfa9de5456094735a0.jpg"/> <link rel="video_src" href="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id=1757757&autoplay=true"/> <link rel="target_url" href="http://www.collegehumor.com/video:1757757"/> </fb:share-button>
XML;
        $result = Services_Facebook_Share::parse($html);
        $this->assertTrue(!empty($result['image']));
        $this->assertTrue(!empty($result['image']['src']));
        $this->assertTrue(!empty($result['video']));
        $this->assertTrue(!empty($result['video']['src']));
        $this->assertTrue(!empty($result['video']['type']));
        $this->assertTrue(!empty($result['video']['height']));
        $this->assertTrue(!empty($result['video']['width']));
        $this->assertTrue(!empty($result['medium']));
        $this->assertTrue(!empty($result['title']));
        $this->assertTrue(!empty($result['description']));
    }

}

?>
