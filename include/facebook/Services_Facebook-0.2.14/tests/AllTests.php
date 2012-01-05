<?php

require_once 'PHPUnit/Framework.php';
require_once 'tests/Services/AllTests.php';

class AllTests
{

    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services Facebook');
        $suite->addTest(Services_AllTests::suite());

        return $suite;
    }

}

?>
