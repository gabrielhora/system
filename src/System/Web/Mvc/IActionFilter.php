<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

interface IActionFilter
{
    public function OnActionExecuting(ActionExecutingContext $context);
    public function OnActionExecuted(ActionExecutedContext $context);
    public function OnResultExecuting(ResultExecutingContext $context);
    public function OnResultExecuted(ResultExecutedContext $context);
}