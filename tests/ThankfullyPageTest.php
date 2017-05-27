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

        $queryString = array(
            'k' => 'v',
            'z' => 'w'
        );

        $page->setQueryString($queryString);

        $this->assertTrue((Session::get('Thankfully.PageQueryString') == $queryString), 'The query string was not saved to session correctly');
        $this->assertTrue(strstr('?k=v&z=w', $page->Link()), 'The query string was not appended to the link');
    }
}