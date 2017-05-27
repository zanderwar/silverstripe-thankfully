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
        $this->assertContains(http_build_query($queryString), $page->Link(), 'The query string was not appended to the link');

        $page->setPageTitle('Thank You! :)');
        $this->assertEquals('Thank You! :)', Session::get('Thankfully.PageTitle'), 'The page title was not saved to session correctly');

        $page->setPageContent('<p>Hello World</p>');
        $this->assertEquals('<p>Hello World</p>', Session::get('Thankfully.PageContent'), 'The page content was not saved to session correctly');

        $page->setReturnTo('/my/cool/page');
        $this->assertEquals('/my/cool/page', Session::get('Thankfully.PageReturnTo'), 'The return to URL was not saved to session correctly');
    }
}