<?php

/**
 * Class ThankfullyPageTest
 */
class ThankfullyPageTest extends SapphireTest
{
    public static $fixture_file = 'ThankfullyPageTest.yml';

    public function testThankfullyPage()
    {
        /** @var ThankfullyPage $page */
        $page = $this->objFromFixture('ThankfullyPage', 'default_page');

        $this->assertTrue(($page->URLSegment == 'thank-you'));
    }
}