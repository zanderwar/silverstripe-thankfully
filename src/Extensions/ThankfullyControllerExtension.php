<?php

/**
 * Class ThankfullyControllerExtension
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyControllerExtension extends Extension
{
    /**
     * Returns a pre-configured ThankYou page
     *
     * @return ThankfullyPage
     */
    public function getThankYouPage()
    {
        /** @var ThankfullyPage $page */
        $page = ThankfullyPage::get()->first();

        $page->setPageTitle(($this->owner->ThankYouTitle) ? $this->owner->ThankYouTitle : $page->Title);
        $page->setPageContent(($this->owner->ThankYouContent) ? $this->owner->ThankYouContent : $page->Content);
        $page->setReturnTo($this->owner->Link());
        $page->setQueryString(
            ThankfullyQueryStringPair::get()->filter('PageID', $this->owner->ID)->map('Key', 'Value')->toArray()
        );

        return $page->setIsThankful();
    }
}