<?php

/**
 * Class ThankfullyExtension
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyExtension extends DataExtension
{
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        $existingPages = $this->owner->get();

        /** @var Page $existingPage */
        foreach ($existingPages as $existingPage) {
            /** @var ThankfullyPage $page */
            $page = ThankfullyPage::get()->filter('ParentID', $existingPage->ID)->first();

            if (!$page) {
                Versioned::reading_stage('Stage');
                $page = ThankfullyPage::create();
                $page->Title = _t('Thankfully.DefaultPageTitle', 'Thank You');
                $page->MenuTitle = _t('Thankfully.DefaultPageTitle', 'Thank You');
                $page->Content = '<p>' . _t('Thankfully.DefaultPageFirstParagraph', 'Thank you for your submission') . '</p>';
                $page->URLSegment = 'thank-you';
                $page->ParentID = $existingPage->ID;
                $page->write();
                $page->publish('Stage', 'Live');

                DB::alteration_message(sprintf('Thank You page for %s has been created', get_class($this->owner)), 'created');
            }
        }
    }
}