<?php
namespace System\Web\Mvc;

class Controller extends ControllerBase
{
    /** @var UrlHelper */
    private static $urlHelper;

    /**
     * @return UrlHelper
     */
    public function Url()
    {
        if (self::$urlHelper === null)
            self::$urlHelper = new UrlHelper($this->HttpContext());
        return self::$urlHelper;
    }

    public function HttpPost()
    {
        if ($this->HttpContext()->Request()->Method() !== 'POST')
            throw new \Exception("Method not allowed", 405);
    }

    public function Content($content, $contentType = 'text/html')
    {
        return new ContentResult($content, $contentType);
    }

    public function View($model = null, $viewName = null, $masterName = 'Layout')
    {
        return new ViewResult($model, $viewName, $masterName);
    }

    public function Null()
    {
        return new EmptyResult();
    }

    public function File($bytes, $contentType, $fileName)
    {
        return new FileResult($bytes, $contentType, $fileName);
    }

    public function Json($data, $contentType = 'application/json')
    {
        return new JsonResult($data, $contentType);
    }

    public function Redirect($url)
    {
        return new RedirectResult($url);
    }

    public function RedirectPermanent($url)
    {
        return new RedirectResult($url, true);
    }

    public function RedirectToRoute($routeName, $controllerName = null, $actionName = null, array $routeValues = array())
    {
        return new RedirectToRouteResult($routeName, $controllerName, $actionName, $routeValues);
    }

    public function RedirectToRoutePermanent($routeName, $controllerName = null, $actionName = null, array $routeValues = array())
    {
        return new RedirectToRouteResult($routeName, $controllerName, $actionName, $routeValues, true);
    }
} 