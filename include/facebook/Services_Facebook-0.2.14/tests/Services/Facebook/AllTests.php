<?php

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'tests/Services/Facebook/AdminTest.php';
require_once 'tests/Services/Facebook/ApplicationTest.php';
require_once 'tests/Services/Facebook/AuthTest.php';
require_once 'tests/Services/Facebook/BatchTest.php';
require_once 'tests/Services/Facebook/CommonTest.php';
require_once 'tests/Services/Facebook/ConnectTest.php';
require_once 'tests/Services/Facebook/EventsTest.php';
require_once 'tests/Services/Facebook/FBMLTest.php';
require_once 'tests/Services/Facebook/FeedTest.php';
require_once 'tests/Services/Facebook/FQLTest.php';
require_once 'tests/Services/Facebook/FriendsTest.php';
require_once 'tests/Services/Facebook/GroupsTest.php';
require_once 'tests/Services/Facebook/MarketPlaceTest.php';
require_once 'tests/Services/Facebook/PagesTest.php';
require_once 'tests/Services/Facebook/PhotosTest.php';
require_once 'tests/Services/Facebook/ProfileTest.php';
require_once 'tests/Services/Facebook/ShareTest.php';
require_once 'tests/Services/Facebook/StreamTest.php';
require_once 'tests/Services/Facebook/UsersTest.php';

class Services_Facebook_AllTests
{

    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services Facebook');
        $suite->addTestSuite('Services_Facebook_AdminTest');
        $suite->addTestSuite('Services_Facebook_ApplicationTest');
        $suite->addTestSuite('Services_Facebook_AuthTest');
        $suite->addTestSuite('Services_Facebook_BatchTest');
        $suite->addTestSuite('Services_Facebook_CommonTest');
        $suite->addTestSuite('Services_Facebook_ConnectTest');
        $suite->addTestSuite('Services_Facebook_EventsTest');
        $suite->addTestSuite('Services_Facebook_FBMLTest');
        $suite->addTestSuite('Services_Facebook_FeedTest');
        $suite->addTestSuite('Services_Facebook_FQLTest');
        $suite->addTestSuite('Services_Facebook_FriendsTest');
        $suite->addTestSuite('Services_Facebook_GroupsTest');
        $suite->addTestSuite('Services_Facebook_MarketPlaceTest');
        $suite->addTestSuite('Services_Facebook_PagesTest');
        $suite->addTestSuite('Services_Facebook_PhotosTest');
        $suite->addTestSuite('Services_Facebook_ProfileTest');
        $suite->addTestSuite('Services_Facebook_ShareTest');
        $suite->addTestSuite('Services_Facebook_StreamTest');
        $suite->addTestSuite('Services_Facebook_UsersTest');

        return $suite;
    }
}

?>
