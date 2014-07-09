<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

abstract class ActionResult
{
    abstract function ExecuteResult(HttpContext $context, ControllerBase $controller);
}