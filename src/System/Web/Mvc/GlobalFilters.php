<?php
namespace System\Web\Mvc;

class GlobalFilters
{
    /** @var GlobalFilterCollection */
    private static $filters;

    public static function Filters()
    {
        if (static::$filters === null)
        {
            static::$filters = new GlobalFilterCollection(array(
                new HandleErrorFilter()
            ));
        }
        return static::$filters;
    }
} 