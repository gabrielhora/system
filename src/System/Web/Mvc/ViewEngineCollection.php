<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ViewEngineCollection
{
    /** @var IViewEngine[] */
    private $engines;

    public function __construct(array $engines = array())
    {
        $this->engines = $engines;
    }

    public function InsertItem($index, IViewEngine $engine)
    {
        $this->engines[$index] = $engine;
    }

    public function RemoveItem($index)
    {
        unset($this->engines[$index]);
    }

    public function ClearItems()
    {
        $this->engines = array();
    }

    public function SetItem($index, IViewEngine $engine)
    {
        $this->InsertItem($index, $engine);
    }

    public function FileExists(HttpContext $context, $virtualPath)
    {
        foreach ($this->engines as $engine)
        {
            $path = $engine->FileExists($context, $virtualPath);
            if ($path !== false) return $path;
        }
        return null;
    }

    public function FindPartialView(HttpContext $context, $partialName)
    {
        foreach ($this->engines as $engine)
        {
            $partial = $engine->FindPartialView($context, $partialName);
            if ($partial !== null) return $partial;
        }
        return null;
    }

    public function FindView(HttpContext $context, $viewName)
    {
        foreach ($this->engines as $engine)
        {
            $view = $engine->FindView($context, $viewName);
            if ($view !== null) return $view;
        }
        return null;
    }
} 