<?php
namespace System\Web\Mvc;

class ViewEngines
{
    /** @var ViewEngineCollection */
    private static $engines;

    public static function Engines()
    {
        if (static::$engines === null)
        {
            static::$engines = new ViewEngineCollection(array(
                new DefaultViewEngine()
            ));
        }
        return static::$engines;
    }
} 