<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class EmptyResult extends ActionResult
{
    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        // this result does nothing
    }
}