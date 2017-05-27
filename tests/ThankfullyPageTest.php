<?php

/**
 * Class ThankfullyPageTest
 */
class ThankfullyPageTest extends SapphireTest
{
    public function testThankfullyPage()
    {
        $page = ThankfullyPage::get()->first();

        $this->assertTrue(!$page, 'The default page was not created');
    }
}