<?php

/**
 * Class ThankfullyExtension
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class ThankfullyExtension extends DataExtension
{
    /** @var array */
    private static $db = array(
        'ThankYouTitle' => 'Varchar(255)',
        'ThankYouContent' => 'HTMLText',
    );

    /** @var array */
    private static $has_many = array(
        'QueryStrings' => 'ThankfullyQueryStringPair'
    );

    /**
     * {@inheritdoc}
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        parent::updateCMSFields($fields);

        $gridField = GridField::create(
            'QueryStringEditor',
            'Query String Editor',
            ThankfullyQueryStringPair::get()->filter('PageID', $this->owner->ID),
            GridFieldConfig_RecordEditor::create()
        );

        $parentID = $this->owner->ID;

        /** @var GridFieldDetailForm $detailForm */
        $detailForm = $gridField->getConfig()->getComponentByType('GridFieldDetailForm');
        $detailForm->setItemEditFormCallback(function ($form) use ($parentID) {
            /** @var Form $form */
            $fields = $form->Fields();
            $fields->removeByName('PageID');
            $fields->addFieldsToTab(
                'Root.Main',
                array(
                    HiddenField::create('PageID', 'PageID', $parentID)
                )
            );
        });

        $fields->addFieldsToTab(
            'Root.ThankYou',
            array(
                TextField::create('ThankYouTitle', 'Page Title')->setRightTitle('If left blank, the default will be assumed'),
                HtmlEditorField::create('ThankYouContent', 'Page Content')->setRows(5)->setRightTitle('If left blank, the default will be assumed'),
                $gridField
            )
        );
    }
}