<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

abstract class VirtualPathProviderViewEngine implements IViewEngine
{
    /** @var string[] */
    protected $viewLocationFormats;

    /** @var string[] */
    protected $partialViewLocationFormats;

    /**
     * @param \string[] $partialViewLocationFormats
     */
    public function SetPartialViewLocationFormats($partialViewLocationFormats)
    {
        $this->partialViewLocationFormats = $partialViewLocationFormats;
    }

    /**
     * @return \string[]
     */
    public function PartialViewLocationFormats()
    {
        return $this->partialViewLocationFormats;
    }

    /**
     * @param \string[] $viewLocationFormats
     */
    public function SetViewLocationFormats($viewLocationFormats)
    {
        $this->viewLocationFormats = $viewLocationFormats;
    }

    /**
     * @return \string[]
     */
    public function ViewLocationFormats()
    {
        return $this->viewLocationFormats;
    }

    /**
     * @param HttpContext $context
     * @param string $viewPath
     * @return IView
     */
    public abstract function CreateView(HttpContext $context, $viewPath);

    /**
     * @param HttpContext $context
     * @param string $viewPath
     * @return IView
     */
    public abstract function CreatePartialView(HttpContext $context, $viewPath);

    /**
     * @param HttpContext $context
     * @param $virtualPath
     * @return string|bool
     */
    public function FileExists(HttpContext $context, $virtualPath)
    {
        $basePath = $context->BasePath();
        if (strpos($virtualPath, '~') === 0)
            $path = preg_replace('/^~/', $basePath, $virtualPath);
        else
            $path = $basePath . '/' . ltrim($virtualPath, '/');
        return file_exists($path) ? $path : false;
    }

    public function FindPartialView(HttpContext $context, $partialName)
    {
        $routeData = $context->RouteData();
        $controllerName = $routeData !== null ? $routeData->ControllerName() : '';

        foreach ($this->partialViewLocationFormats as $partialViewLocationFormat)
        {
            $partialViewVirtualPath = sprintf($partialViewLocationFormat, $partialName, $controllerName);
            $path = $this->FileExists($context, $partialViewVirtualPath);
            if ($path !== false)
            {
                return $this->CreatePartialView($context, $path);
            }
        }
        return null;
    }

    public function FindView(HttpContext $context, $viewName)
    {
        $routeData = $context->RouteData();
        $controllerName = $routeData !== null ? $routeData->ControllerName() : '';
        $viewPath = null;

        foreach ($this->viewLocationFormats as $viewLocationFormat)
        {
            $viewVirtualPath = sprintf($viewLocationFormat, $viewName, $controllerName);
            $path = $this->FileExists($context, $viewVirtualPath);
            if ($path !== false)
            {
                $viewPath = $path;
                break;
            }
        }

        // create the view
        return $viewPath === null ? null : $this->CreateView($context, $viewPath);
    }
} 