<?php

/**
 * Class ThankfullyQueryStringPair
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 *
 * @property string Key
 * @property string Value
 * @method Page Page
 */
class ThankfullyQueryStringPair extends DataObject
{
    /** @var string */
    private static $singular_name = 'Query String Pair';

    /** @var string */
    private static $plural_name = 'Query String Pairs';

    /** @var array */
    private static $db = array(
        'Key' => 'Varchar(50)',
        'Value' => 'Varchar(50)'
    );

    /** @var array */
    private static $has_one = array(
        'Page' => 'ThankfullyPage'
    );

    /** @var array */
    private static $summary_fields = array(
        'Key' => 'Key',
        'Value' => 'Value'
    );
}