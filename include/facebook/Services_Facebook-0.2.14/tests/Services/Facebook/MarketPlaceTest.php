<?php

require_once 'UnitTestCommon.php';
require_once 'Services/Facebook/MarketPlace/Listing.php';

class Services_Facebook_MarketPlaceTest extends Services_Facebook_UnitTestCommon
{

    public function testCreateListing()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <marketplace_createListing_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">  6007447597 </marketplace_createListing_response>
XML;

        $listing = new Services_Facebook_MarketPlace_Listing;
        $listing->category    = 'foo';
        $listing->subcategory = 'foo';
        $listing->title       = 'foo';
        $listing->description = 'foo';
        $listing->condition   = 'NEW';
        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->createListing($listing);
        $this->assertEquals(6007447597, $result->id);
    }

    public function testGetCategories()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<marketplace_getCategories_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <marketplace_category>FORSALE</marketplace_category>
  <marketplace_category>HOUSING</marketplace_category>
  <marketplace_category>JOBS</marketplace_category>
  <marketplace_category>OTHER</marketplace_category>
  <marketplace_category>FREE</marketplace_category>
  <marketplace_category>FORSALE_WANTED</marketplace_category>
  <marketplace_category>HOUSING_WANTED</marketplace_category>
  <marketplace_category>JOBS_WANTED</marketplace_category>
  <marketplace_category>OTHER_WANTED</marketplace_category>
</marketplace_getCategories_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getCategories();
        $this->assertEquals(count($result), 9);
        foreach ($result as $category) {
            $this->assertTrue(is_string($category));
        }
    }

    public function testGetSubCategories()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<marketplace_getSubCategories_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <marketplace_subcategory>ACCOUNTING</marketplace_subcategory>
  <marketplace_subcategory>ADMINSTRATIVE</marketplace_subcategory>
  <marketplace_subcategory>ADVERTISING</marketplace_subcategory>
  <marketplace_subcategory>ARCHITECTURAL</marketplace_subcategory>
  <marketplace_subcategory>ART</marketplace_subcategory>
  <marketplace_subcategory>SCIENCE</marketplace_subcategory>
  <marketplace_subcategory>BUSINESS</marketplace_subcategory>
  <marketplace_subcategory>CONSULTING</marketplace_subcategory>
  <marketplace_subcategory>SUPPORT</marketplace_subcategory>
  <marketplace_subcategory>EDUCATION</marketplace_subcategory>
  <marketplace_subcategory>ENGINEERING</marketplace_subcategory>
  <marketplace_subcategory>FACEBOOK_PLATFORM</marketplace_subcategory>
  <marketplace_subcategory>LABOR</marketplace_subcategory>
  <marketplace_subcategory>GOVERNMENT</marketplace_subcategory>
  <marketplace_subcategory>HR</marketplace_subcategory>
  <marketplace_subcategory>INVESTMENT</marketplace_subcategory>
  <marketplace_subcategory>LEGAL</marketplace_subcategory>
  <marketplace_subcategory>MANUFACTURING</marketplace_subcategory>
  <marketplace_subcategory>MARKETING</marketplace_subcategory>
  <marketplace_subcategory>MEDICAL</marketplace_subcategory>
  <marketplace_subcategory>REALTY</marketplace_subcategory>
  <marketplace_subcategory>RESEARCH</marketplace_subcategory>
  <marketplace_subcategory>RESTAURANT</marketplace_subcategory>
  <marketplace_subcategory>SALES</marketplace_subcategory>
  <marketplace_subcategory>SECURITY</marketplace_subcategory>
  <marketplace_subcategory>CRAFT</marketplace_subcategory>
  <marketplace_subcategory>SOFTWARE</marketplace_subcategory>
  <marketplace_subcategory>IT</marketplace_subcategory>
  <marketplace_subcategory>TV</marketplace_subcategory>
  <marketplace_subcategory>TRANSPORTATION</marketplace_subcategory>
  <marketplace_subcategory>WEB</marketplace_subcategory>
  <marketplace_subcategory>WRITING</marketplace_subcategory>
  <marketplace_subcategory>GENERAL</marketplace_subcategory>
</marketplace_getSubCategories_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getCategories();
        $this->assertEquals(count($result), 33);
        foreach ($result as $category) {
            $this->assertTrue(is_string($category));
        }
    }

    public function testGetListings()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<marketplace_getListings_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <listing>
    <listing_id>19379702521</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=19379702521</url>
    <title>For sale: Short Guide to Writing About Art by Sylvan Barnet</title>
    <description>I'm looking to sell Short Guide to Writing About Art by Sylvan Barnet</description>
    <price>19.6</price>
    <poster>683226814</poster>
    <update_time>1213367999</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780136138556</isbn>
  </listing>
  <listing>
    <listing_id>17194280734</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=17194280734</url>
    <title>For sale: Fundamentals of Trigonometry by Earl Swokowski</title>
    <description>I'm looking to sell Fundamentals of Trigonometry by Earl Swokowski</description>
    <price>50.95</price>
    <poster>683226814</poster>
    <update_time>1213705552</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780534361280</isbn>
  </listing>
  <listing>
    <listing_id>28823644704</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=28823644704</url>
    <title>For sale: Pride and Prejudice by Jane Austen</title>
    <description>It's hard to believe that Jane Austen wrote the sophisticated and acerbic PRIDE AND PREJUDICE when she was only 21 years old, in 1797. Originally entitled FIRST IMPRESSIONS, the novel was rejected, revised, retitled, and finally published--anonymously--in 1813, only four years before Austen's untimely death. In PRIDE AND PREJUDICE, Austen calls on her sharp observations of vanity, venality, pomposity, and downright nuttiness in a story about a respectable but far from wealthy family full of daughters--girls who desperately need to find husbands if they are to have any kind of economic security. The eldest of the Bennett family, Elizabeth, is a bright, opinionated, and complacent young woman whose reaction to an offer of marriage from her wealthy but impossibly arrogant suitor, Fitzwilliam Darcy, is revulsion. But in the course of the story both Elizabeth and Darcy learn important lessons about their own folly and blindness, and about the dangers of superficial judgements. As the two perform their elaborate courtship dance, Austen surrounds them with some of her most uproariously clueless characters--from the wacky Mrs. Bennett to the wonderfully unctuous Mr. Collins, another of Elizabeth's admirers. PRIDE AND PREJUDICE is, of course, a highly satisfying and offbeat love story, but it is also an unparalleled examination of human nature at both its best and its hilarious worst.

Powered by muze(r). MuzeBooks(tm) and Out of Print MuzeBooks(tm) Copyright 2007. For personal non-commercial use only. All rights reserved. Portions of this content may be the property of Baker &amp; Taylor, Inc. or its licensors and shall be subject to copyright and all other protections under the law.</description>
    <price>2.95</price>
    <poster>683226814</poster>
    <update_time>1213318731</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780743487597</isbn>
  </listing>
  <listing>
    <listing_id>19452031005</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=19452031005</url>
    <title>For sale: History of Far Eastern Art by Sherman E. Lee</title>
    <description>I'm looking to sell History of Far Eastern Art by Sherman E. Lee</description>
    <price>75</price>
    <poster>683226814</poster>
    <update_time>1212720811</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780131833661</isbn>
  </listing>
  <listing>
    <listing_id>16448293469</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=16448293469</url>
    <title>For sale: Inconvenient Truth by Al Gore</title>
    <description>In the hopes of raising public awareness and affecting public policy, former Vice-President Al Gore issues this red flag on one of the key topics with which he has long been identified: the issue of global warming. Gore synthesizes the latest scientific findings, which indicate that the problem is more acute than just a few years ago. There are clear signs that the environment is changing: more violent and destructive weather conditions, changes in seasons, and significant melting of the polar regions due to increased temperatures. Gore explains the effects of each of these on the land and sea, and ultimately on populations.In an even-tempered tone, Mr. Gore takes a firm stand that addressing global warming is a priority, and he offers clear actions that need to be taken. He says that we do not need to sacrifice a robust economy, we just have to act prudently--but act we must on this &quot;global emergency,&quot; as he calls it.

Powered by muze(r). MuzeBooks(tm) and Out of Print MuzeBooks(tm) Copyright 2007. For personal non-commercial use only. All rights reserved. Portions of this content may be the property of Baker &amp; Taylor, Inc. or its licensors and shall be subject to copyright and all other protections under the law.</description>
    <price>11.95</price>
    <poster>683226814</poster>
    <update_time>1213909532</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9781594865671</isbn>
  </listing>
  <listing>
    <listing_id>20805011254</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=20805011254</url>
    <title>For sale: Sams Teach Yourself Php, Mysql And Apache All in One by Julie C. Meloni</title>
    <description>I'm looking to sell Sams Teach Yourself Php, Mysql And Apache All in One by Julie C. Meloni</description>
    <price>24.99</price>
    <poster>683226814</poster>
    <update_time>1213006196</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780672327254</isbn>
  </listing>
  <listing>
    <listing_id>16175938859</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=16175938859</url>
    <title>Java Software Solutions Foundations of program design</title>
    <description>Pearson International Edition / Fifth Edition</description>
    <price>30</price>
    <poster>683226814</poster>
    <update_time>1215078799</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>0321373375</isbn>
  </listing>
  <listing>
    <listing_id>23792841280</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=23792841280</url>
    <title>For sale: American Pageant:A History of the Republic by David M. Kennedy</title>
    <description>I'm looking to sell American Pageant:A History of the Republic by David M. Kennedy</description>
    <price>35</price>
    <poster>683226814</poster>
    <update_time>0</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780618479283</isbn>
  </listing>
  <listing>
    <listing_id>7411213171</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=7411213171</url>
    <title>For sale: Intermediate Algebra by Mark Dugopolski</title>
    <description>

Jeff adds:
Like new condition, sorry no CD</description>
    <price>60</price>
    <poster>683226814</poster>
    <update_time>0</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780072443936</isbn>
  </listing>
  <listing>
    <listing_id>5609466389</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=5609466389</url>
    <title>PSP Only used once - w/ 4 movies</title>
    <description>This is for a practically brand new PSP and 4 movies.  I used it once on a airplane and thats it.  Looks brand new.  Has original box and most packaging.  Movies are:  FF7 Advent Children, Not another teen movie, 5th element, and starship troopers.</description>
    <price>150</price>
    <poster>683226814</poster>
    <update_time>1214971371</update_time>
    <category>FORSALE</category>
    <subcategory>ELECTRONICS</subcategory>
    <image_urls list="true">
      <image_urls_elt>http://photos-c.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570778_3028.jpg</image_urls_elt>
      <image_urls_elt>http://photos-d.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570779_3337.jpg</image_urls_elt>
      <image_urls_elt>http://photos-e.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570780_3648.jpg</image_urls_elt>
      <image_urls_elt>http://photos-f.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570781_3957.jpg</image_urls_elt>
    </image_urls>
    <condition>USED</condition>
  </listing>
</marketplace_getListings_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getListings(null, 683226814);
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result as $item) {
            $this->assertObjectHasAttribute('listing_id', $item);
        }
    }

    public function testsRemoveListing()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?> <marketplace_removeListing_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">1</marketplace_removeListing_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->removeListing(123123);
        $this->assertTrue($result);
    }

    public function testsSearch()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<marketplace_getListings_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <listing>
    <listing_id>19379702521</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=19379702521</url>
    <title>For sale: Short Guide to Writing About Art by Sylvan Barnet</title>
    <description>I'm looking to sell Short Guide to Writing About Art by Sylvan Barnet</description>
    <price>19.6</price>
    <poster>683226814</poster>
    <update_time>1213367999</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780136138556</isbn>
  </listing>
  <listing>
    <listing_id>17194280734</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=17194280734</url>
    <title>For sale: Fundamentals of Trigonometry by Earl Swokowski</title>
    <description>I'm looking to sell Fundamentals of Trigonometry by Earl Swokowski</description>
    <price>50.95</price>
    <poster>683226814</poster>
    <update_time>1213705552</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780534361280</isbn>
  </listing>
  <listing>
    <listing_id>28823644704</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=28823644704</url>
    <title>For sale: Pride and Prejudice by Jane Austen</title>
    <description>It's hard to believe that Jane Austen wrote the sophisticated and acerbic PRIDE AND PREJUDICE when she was only 21 years old, in 1797. Originally entitled FIRST IMPRESSIONS, the novel was rejected, revised, retitled, and finally published--anonymously--in 1813, only four years before Austen's untimely death. In PRIDE AND PREJUDICE, Austen calls on her sharp observations of vanity, venality, pomposity, and downright nuttiness in a story about a respectable but far from wealthy family full of daughters--girls who desperately need to find husbands if they are to have any kind of economic security. The eldest of the Bennett family, Elizabeth, is a bright, opinionated, and complacent young woman whose reaction to an offer of marriage from her wealthy but impossibly arrogant suitor, Fitzwilliam Darcy, is revulsion. But in the course of the story both Elizabeth and Darcy learn important lessons about their own folly and blindness, and about the dangers of superficial judgements. As the two perform their elaborate courtship dance, Austen surrounds them with some of her most uproariously clueless characters--from the wacky Mrs. Bennett to the wonderfully unctuous Mr. Collins, another of Elizabeth's admirers. PRIDE AND PREJUDICE is, of course, a highly satisfying and offbeat love story, but it is also an unparalleled examination of human nature at both its best and its hilarious worst.

Powered by muze(r). MuzeBooks(tm) and Out of Print MuzeBooks(tm) Copyright 2007. For personal non-commercial use only. All rights reserved. Portions of this content may be the property of Baker &amp; Taylor, Inc. or its licensors and shall be subject to copyright and all other protections under the law.</description>
    <price>2.95</price>
    <poster>683226814</poster>
    <update_time>1213318731</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780743487597</isbn>
  </listing>
  <listing>
    <listing_id>19452031005</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=19452031005</url>
    <title>For sale: History of Far Eastern Art by Sherman E. Lee</title>
    <description>I'm looking to sell History of Far Eastern Art by Sherman E. Lee</description>
    <price>75</price>
    <poster>683226814</poster>
    <update_time>1212720811</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780131833661</isbn>
  </listing>
  <listing>
    <listing_id>16448293469</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=16448293469</url>
    <title>For sale: Inconvenient Truth by Al Gore</title>
    <description>In the hopes of raising public awareness and affecting public policy, former Vice-President Al Gore issues this red flag on one of the key topics with which he has long been identified: the issue of global warming. Gore synthesizes the latest scientific findings, which indicate that the problem is more acute than just a few years ago. There are clear signs that the environment is changing: more violent and destructive weather conditions, changes in seasons, and significant melting of the polar regions due to increased temperatures. Gore explains the effects of each of these on the land and sea, and ultimately on populations.In an even-tempered tone, Mr. Gore takes a firm stand that addressing global warming is a priority, and he offers clear actions that need to be taken. He says that we do not need to sacrifice a robust economy, we just have to act prudently--but act we must on this &quot;global emergency,&quot; as he calls it.

Powered by muze(r). MuzeBooks(tm) and Out of Print MuzeBooks(tm) Copyright 2007. For personal non-commercial use only. All rights reserved. Portions of this content may be the property of Baker &amp; Taylor, Inc. or its licensors and shall be subject to copyright and all other protections under the law.</description>
    <price>11.95</price>
    <poster>683226814</poster>
    <update_time>1213909532</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9781594865671</isbn>
  </listing>
  <listing>
    <listing_id>20805011254</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=20805011254</url>
    <title>For sale: Sams Teach Yourself Php, Mysql And Apache All in One by Julie C. Meloni</title>
    <description>I'm looking to sell Sams Teach Yourself Php, Mysql And Apache All in One by Julie C. Meloni</description>
    <price>24.99</price>
    <poster>683226814</poster>
    <update_time>1213006196</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780672327254</isbn>
  </listing>
  <listing>
    <listing_id>16175938859</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=16175938859</url>
    <title>Java Software Solutions Foundations of program design</title>
    <description>Pearson International Edition / Fifth Edition</description>
    <price>30</price>
    <poster>683226814</poster>
    <update_time>1215078799</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>0321373375</isbn>
  </listing>
  <listing>
    <listing_id>23792841280</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=23792841280</url>
    <title>For sale: American Pageant:A History of the Republic by David M. Kennedy</title>
    <description>I'm looking to sell American Pageant:A History of the Republic by David M. Kennedy</description>
    <price>35</price>
    <poster>683226814</poster>
    <update_time>0</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780618479283</isbn>
  </listing>
  <listing>
    <listing_id>7411213171</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=7411213171</url>
    <title>For sale: Intermediate Algebra by Mark Dugopolski</title>
    <description>

Jeff adds:
Like new condition, sorry no CD</description>
    <price>60</price>
    <poster>683226814</poster>
    <update_time>0</update_time>
    <category>FORSALE</category>
    <subcategory>BOOKS</subcategory>
    <image_urls list="true"/>
    <condition>USED</condition>
    <isbn>9780072443936</isbn>
  </listing>
  <listing>
    <listing_id>5609466389</listing_id>
    <url>http://www.facebook.com/marketplace/listing.php?classified_id=5609466389</url>
    <title>PSP Only used once - w/ 4 movies</title>
    <description>This is for a practically brand new PSP and 4 movies.  I used it once on a airplane and thats it.  Looks brand new.  Has original box and most packaging.  Movies are:  FF7 Advent Children, Not another teen movie, 5th element, and starship troopers.</description>
    <price>150</price>
    <poster>683226814</poster>
    <update_time>1214971371</update_time>
    <category>FORSALE</category>
    <subcategory>ELECTRONICS</subcategory>
    <image_urls list="true">
      <image_urls_elt>http://photos-c.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570778_3028.jpg</image_urls_elt>
      <image_urls_elt>http://photos-d.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570779_3337.jpg</image_urls_elt>
      <image_urls_elt>http://photos-e.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570780_3648.jpg</image_urls_elt>
      <image_urls_elt>http://photos-f.ll.facebook.com/photos-ll-sf2p/v129/3/56/683226814/n683226814_570781_3957.jpg</image_urls_elt>
    </image_urls>
    <condition>USED</condition>
  </listing>
</marketplace_getListings_response>
XML;

        $this->mockSendRequest($response);
        $this->instance->sessionKey = '123123sfsdf-123123';
        $result = $this->instance->search('foo');
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result as $item) {
            $this->assertObjectHasAttribute('listing_id', $item);
        }
    }

}

?>
