<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_FeedTest extends Services_Facebook_UnitTestCommon
{

    public function testPublishStoryToUser()
    {
        $this->markTestIncomplete();
        $response = <<<XML
XML;

        $this->mockSendRequest($response);
        $images = array(
            array(
                'src'  => 'http://example.com/foo.jpg',
                'href' => 'http://example.com/images.php?image=foo'
            ),
            array(
                'src'  => 'http://example.com/bar.jpg',
                'href' => 'http://example.com/images.php?image=bar'
            )
        );
        $result = $this->instance->publishStoryToUser('w00t', 'body', $images);
        $this->assertEquals('', $result);
    }

    public function testPublishActionOfUser()
    {
        $this->markTestIncomplete();
        $response = <<<XML
XML;

        $this->mockSendRequest($response);
        $images = array(
            array(
                'src'  => 'http://example.com/foo.jpg',
                'href' => 'http://example.com/images.php?image=foo'
            ),
            array(
                'src'  => 'http://example.com/bar.jpg',
                'href' => 'http://example.com/images.php?image=bar'
            )
        );
 
        $result = $this->instance->publishActionOfUser('w00t', 'body', $images);
        $this->assertEquals('', $result);
    }

    public function testPublishTemplatizedAction()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <feed_publishTemplatizedAction_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</feed_publishTemplatizedAction_response>
XML;

        $images = array(
            array(
                'src'  => 'http://example.com/foo.jpg',
                'href' => 'http://example.com/images.php?image=foo'
            ),
            array(
                'src'  => 'http://example.com/bar.jpg',
                'href' => 'http://example.com/images.php?image=bar'
            )
        );
        $data = array(
            'title_data' => 'bar'
        );
        $this->mockSendRequest($response);
        $result = $this->instance->publishTemplatizedAction('template',
            $data, $images);
        $this->assertTrue($result);
    }

    public function testRegisterTemplateBundle()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <feed_registerTemplateBundle_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/">  17876842716 </feed_registerTemplateBundle_response>
XML;

        $this->mockSendRequest($response);
        $oneLineStoryTpls = array('foo');
        $shortStoryTpls   = array('foo');
        $fullStoryTpl = array('foo');
        $result = $this->instance->registerTemplateBundle($oneLineStoryTpls,
            $shortStoryTpls, $fullStoryTpl);
        $this->assertEquals(17876842716, $result);
    }

    public function testGetRegisteredTemplateBundles()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <feed_getRegisteredTemplateBundles_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true"> <template_bundle> <one_line_story_templates list="true"> <one_line_story_template>{*actor*} is playing {*game*} with {*target*}.</one_line_story_template> </one_line_story_templates> <short_story_templates list="true"> <short_story_template> <template_title>{*actor*} is playing {*game*} with {*target*}.</template_title> <template_body>{*actor*} is playing {*game*} with {*target*}.</template_body> </short_story_template> </short_story_templates> <full_story_template> <template_title>{*actor*} is playing {*game*} with {*target*}</template_title> <template_body>{*actor*} is playing {*game*} with {*target*}.</template_body> </full_story_template> <time_created>1213995448</time_created> <template_bundle_id>17869702716</template_bundle_id> </template_bundle> <template_bundle> <one_line_story_templates list="true"> <one_line_story_template>{*actor*} just challenged {*target*} to a game of {*game*}.</one_line_story_template> </one_line_story_templates> <short_story_templates list="true"> <short_story_template> <template_title>{*actor*} just challenged {*target*} to a game of {*game*}.</template_title> <template_body>{*actor*} just challenged {*target*} to a game of {*game*}.</template_body> </short_story_template> </short_story_templates> <full_story_template> <template_title>{*actor*} just challenged {*target*} to a game of {*game*}.</template_title> <template_body>{*actor*} just challenged {*target*} to a game of {*game*}.</template_body> </full_story_template> <time_created>1212692444</time_created> <template_bundle_id>16487752716</template_bundle_id> </template_bundle> </feed_getRegisteredTemplateBundles_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getRegisteredTemplateBundles();
        $this->assertType('SimpleXMLElement', $result);
    }

    public function testGetRegisteredTemplateBundleByID()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <feed_getRegisteredTemplateBundleById_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"> <one_line_story_templates list="true"> <one_line_story_template>{*actor*} is playing {*game*} with {*target*}.</one_line_story_template> </one_line_story_templates> <short_story_templates list="true"> <short_story_template> <template_title>{*actor*} is playing {*game*} with {*target*}.</template_title> <template_body>{*actor*} is playing {*game*} with {*target*}.</template_body> </short_story_template> </short_story_templates> <full_story_template> <template_title>{*actor*} is playing {*game*} with {*target*}</template_title> <template_body>{*actor*} is playing {*game*} with {*target*}.</template_body> </full_story_template> <time_created>1213995448</time_created> <template_bundle_id>17869702716</template_bundle_id> </feed_getRegisteredTemplateBundleById_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getRegisteredTemplateBundleByID(123);
        $this->assertType('SimpleXMLElement', $result);
    }

    public function testDeactivateTemplateBundleByID()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <feed_deactivateTemplateBundleByID_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/"> 1 </feed_deactivateTemplateBundleByID_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->deactivateTemplateBundleByID(123);
        $this->assertTrue($result);
    }

    public function testPublishUserAction()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<feed_publishUserAction_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <feed_publishUserAction_response_elt>1</feed_publishUserAction_response_elt>
  </feed_publishUserAction_response>
XML;

        $this->mockSendRequest($response);
        $data = array(
            'foo' => 'bar'
        );
        $ids = array(123, 12);
        $result = $this->instance->publishUserAction(123,
            $data, $ids, 'body');
        $this->assertType('boolean', $result);
    }

}

?>
