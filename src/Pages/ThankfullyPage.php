<?php

/**
 * Class ThankfullyPage
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 * @method HasManyList QueryStrings
 */
class ThankfullyPage extends Page
{
    /** @var array */
    private static $db = array(
        'AlwaysAllowed' => 'Boolean',
        'ConversionScript'=>'HTMLText'
    );

    /** @var string */
    private static $description = 'This page is created automatically and is used to generate a Thank You page based on certain parameters provided at the time.';

    /** @var array */
    private static $defaults = array(
        'ShowInMenus' => false,
        'ShowInSearch' => false
    );

    private static $has_many = array(
        'QueryStrings' => 'ThankfullyQueryStringPair'
    );

    /** @var string A url that can be provided to the interface to return to */
    protected $returnTo;

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

        /** @var TextField $segmentField */
        $segmentField = $fields->dataFieldByName('URLSegment');
        $segmentField->setRightTitle('You can modify this without affecting anything that links here');

        /** @var HtmlEditorField $contentField */
        $contentField = $fields->dataFieldByName('Content');
        $contentField->setRows(5);

        $gridField = GridField::create(
            'QueryStringEditor',
            'Query String Editor',
            $this->QueryStrings(),
            GridFieldConfig_RecordEditor::create()
        );

        $fields->addFieldsToTab(
            'Root.Main',
            array(
                DropdownField::create('AlwaysAllowed', 'Always Allowed?', array('Disabled', 'Enabled'))->setRightTitle('If enabled, a visitor can view this page with no reason to be thanked'),
                $gridField,
                TextareaField::create('ConversionScript','Add conversion scripts here.')
            )
        );

        return $fields;
    }

    /**
     * Must be used when instantiating manually (e.g when you're not using {@link ::prepare()})
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setAllowed($bool = true)
    {
        if ($bool) {
            Session::set('Thankfully.Allowed_' . $this->ID, true);
        } else {
            unset($_SESSION['Thankfully']['Allowed_' . $this->ID]);
        }

        return $this;
    }

    /**
     * Set the "return to" URL. A user won't be automatically redirected here instead the template is
     * given a $ReturnTo method which can be used to provide a "Return to Previous Page" button
     *
     * @param string|Page $returnTo
     *
     * @return $this
     */
    public function setReturnTo($returnTo)
    {
        if ($returnTo instanceof Page) {
            $returnTo = $returnTo->Link();
        }

        Session::set('Thankfully.PageReturnTo_' . $this->ID, $returnTo);

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

        if (!$this->QueryStrings()->count()) {
            return $link;
        }

        $queryStrings = array();

        /** @var ThankfullyQueryStringPair $queryString */
        foreach ($this->QueryStrings() as $queryString) {
            $queryStrings[$queryString->Key] = $queryString->Value;
        }

        $query = http_build_query($queryStrings);

        return $link . '?' . $query;
    }
}

/**
 * Class ThankfullyPage_Controller
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 * @method HasManyList QueryStrings
 * @method Page Parent
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
        if (!$this->AlwaysAllowed && !Session::get('Thankfully.Allowed_' . $this->ID)) {
            return $this->render(
                array(
                    'Title' => _t('Thankfully.PageNotReadyTitle', 'Woops!'),
                    'Content' => _t('Thankfully.PageNotReadyContent', 'You have reached this page incorrectly'),
                    'ReturnTo' => $this->getReturnTo()
                )
            );
        }

        $params = array(
            'Allowed' => true,
        );

        $this->data()->setAllowed(false);

        unset($_SESSION['Thankfully']['PageReturnTo_' . $this->ID]);

        return $this->render($params);
    }

    /**
     * @return null|string
     */
    public function getReturnTo()
    {
        $customReturnTo = Session::get('Thankfully.PageReturnTo_' . $this->ID);
        return ($customReturnTo) ? $customReturnTo : (($this->Parent()->exists()) ? $this->Parent()->Link() : null);
    }
}
