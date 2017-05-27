<?php

/**
 * Class ThankfullyControllerExtension
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyControllerExtension extends Extension
{
    /**
     * @return string
     */
    public function getThankYouPage()
    {
        return ThankfullyPage::get()->filter('ParentID', $this->owner->ID)->first();
    }
}