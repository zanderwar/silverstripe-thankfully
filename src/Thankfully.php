<?php

/**
 * Class Thankfully
 *
 * @author Reece Alexander <reece@steadlane.com.au>
 */
class Thankfully extends Object
{
    /**
     * @return ArrayData
     */
    public static function getSession()
    {
        return ArrayData::create(
            array(
                'Status' => Session::get('Thankfully.ShouldBeThankful'),
                'Title' => Session::get('Thankfully.PageTitle'),
                'Content' => Session::get('Thankfully.PageContent'),
                'ReturnTo' => Session::get('Thankfully.PageReturnTo'),
                'QueryString' => Session::get('Thankfully.PageQueryString'),
            )
        );
    }

    /**
     * Destroys the session
     */
    public static function destroySession()
    {
        unset(
            $_SESSION['Thankfully']
        );
    }
}