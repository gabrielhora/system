<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

interface IView
{
    /**
     * @param HttpContext $context
     * @param $model
     * @return string
     */
    public function Render(HttpContext $context, $model = null);
}