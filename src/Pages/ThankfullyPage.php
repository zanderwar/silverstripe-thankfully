<?php

/**
 * Class ThankfullyPage
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyPage extends Page
{
    /** @var array */
    private static $db = array(
        'ForceDefaults' => 'Boolean'
    );

    /** @var string */
    private static $description = 'This page is created automatically and is used to generate a Thank You page based on certain parameters provided at the time.';

    /**
     * Allows a developer to easily configure the Thank You page
     *
     * @param $title
     * @param $content
     * @param string|Page|null $returnTo
     * @param array|null $queryStringPairs An array of key value pairs used to form a query string
     *
     * @return static
     */
    public static function prepare($title, $content, $returnTo = null, array $queryStringPairs = array())
    {
        /** @var static $page */
        $page = static::get()->first();

        if (!$page) {
            user_error(
                'A ThankfullyPage does not exist. Please run a dev/build first.',
                E_USER_ERROR
            );
        }

        $page->setIsThankful();
        $page->setTitle($title);
        $page->setContent($content);
        $page->setReturnTo($returnTo);
        $page->setQueryString($queryStringPairs);

        return $page;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            array(
                'MenuTitle',
                'Metadata',
            )
        );

        /** @var TextField $titleField */
        $titleField = $fields->dataFieldByName('Title');
        $titleField->setTitle('Default Title');
        $titleField->setRightTitle('This title will be used as a default, however it can be overridden by the pages that send visitors here.');

        /** @var TextField $segmentField */
        $segmentField = $fields->dataFieldByName('URLSegment');
        $segmentField->setRightTitle('You can modify this without affecting anything that links here');

        /** @var HtmlEditorField $contentField */
        $contentField = $fields->dataFieldByName('Content');
        $contentField->setTitle('Default Content');
        $contentField->setRightTitle('This content will be used as a default, however it can be overridden by the pages that send visitors here.');
        $contentField->setRows(5);

        $fields->addFieldsToTab(
            'Root.Main',
            array(
                CheckboxField::create('ForceDefaults', 'Force Defaults?')
            )
        );

        return $fields;
    }

    /**
     * Create the page if it does not exist
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        $existingThankYouPage = self::get()->first();

        if (!$existingThankYouPage) {
            $page = ThankfullyPage::create();
            $page->Title = 'Thank You';
            $page->URLSegment = 'thank-you';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->write();
            $page->publish('Stage', 'Live');

            DB::alteration_message('Default Thank You Page Created', 'created');
        }
    }

    /**
     * Static accessor to rapidly retrieve the first record
     *
     * @return static
     */
    public static function getFirst()
    {
        /** @var static $first */
        $first = static::get()->first();

        return $first;
    }

    /**
     * Must be used when instantiating manually (e.g when you're not using {@link ::prepare()})
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setIsThankful($bool = true)
    {
        if ($bool) {
            Session::set('Thankfully.ShouldBeThankful', true);
        } else {
            unset($_SESSION['ShouldBeThankful']);
        }

        return $this;
    }

    /**
     * Sets the Page title. If not provided will fallback to the default.
     * If no default is found then the page will have no title!
     *
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        Session::set('Thankfully.PageTitle', $title);

        return $this;
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        Session::set('Thankfully.PageContent', $content);

        return $this;
    }

    /**
     * Set the "return to" URL. A user won't be automatically redirected here instead the template is
     * given a $ReturnTo method which can be used to provide a "Return to Previous Page" button
     *
     * @param string|Page $url
     *
     * @return $this
     */
    public function setReturnTo($url)
    {
        if ($url instanceof Page) {
            $url = $url->Link();
        }

        Session::set('Thankfully.PageReturnTo', $url);

        return $this;
    }

    /**
     * @param array $keyValuePair
     * @return $this
     */
    public function setQueryString(array $keyValuePair)
    {
        Session::set('Thankfully.PageQueryString', $keyValuePair);

        return $this;
    }

    /**
     * Link override to include query string for conversion tracking
     *
     * @param null $action
     * @return string
     */
    public function Link($action = null)
    {
        $link = parent::Link($action);

        if (!Session::get('Thankfully.PageQueryString')) {
            return $link;
        }

        $query = http_build_query(Session::get('Thankfully.PageQueryString'));

        return $link . '?' . $query;
    }
}

/**
 * Class ThankfullyPage_Controller
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyPage_Controller extends Page_Controller
{
    /** @var array */
    private static $allowed_actions = array(
        'index'
    );

    /**
     * You should set a session variable "ShouldBeThankful" before sending a visitor here
     *
     * @return string
     */
    public function index()
    {
        if (!Session::get('Thankfully.ShouldBeThankful')) {
            return $this->render(
                array(
                    'Title' => 'Woops!',
                    'Content' => 'You have reached this page incorrectly'
                )
            );
        }

        $params = Thankfully::getSession()->toMap();
        Thankfully::destroySession();

        return $this->render($params);
    }

    /**
     * Link override to include query string for conversion tracking
     *
     * @param null $action
     * @return string
     */
    public function Link($action = null)
    {
        $link = parent::Link($action);

        if (!Session::get('Thankfully.PageQueryString')) {
            return $link;
        }

        $query = http_build_query(Session::get('Thankfully.PageQueryString'));

        return $link . '?' . $query;
    }
}