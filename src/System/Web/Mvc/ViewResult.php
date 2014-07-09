<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ViewResult extends ActionResult
{
    /** @var object */
    protected $model;

    /** @var string */
    protected $viewName;

    public function __construct($model = null, $viewName = null)
    {
        $this->model = $model;
        $this->viewName = $viewName;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $routeData = $context->RouteData();
        $viewName = $this->viewName !== null ? $this->viewName : $routeData->ActionName();
        $view = ViewEngines::Engines()->FindView($context, $viewName);
        if ($view !== null)
        {
            $result = $view->Render($context, $this->model);
            $context->Response()->Write($result);
        }
        else
        {
            throw new \Exception("View not found $viewName");
        }
    }
}