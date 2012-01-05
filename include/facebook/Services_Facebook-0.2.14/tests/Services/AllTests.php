<?php

require_once 'PHPUnit/Framework.php';
require_once 'tests/Services/Facebook/AllTests.php';

class Services_AllTests
{

    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services Facebook');
        $suite->addTest(Services_Facebook_AllTests::suite());

        return $suite;
    }

}

?>
