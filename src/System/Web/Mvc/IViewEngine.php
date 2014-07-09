<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

interface IViewEngine
{
    /**
     * Return the physical path of the view or false if not found
     * @param HttpContext $context
     * @param string $virtualPath
     * @return string|bool
     */
    public function FileExists(HttpContext $context, $virtualPath);

    /**
     * @param HttpContext $context
     * @param string $partialName
     * @return IView
     */
    public function FindPartialView(HttpContext $context, $partialName);

    /**
     * @param HttpContext $context
     * @param string $viewName
     * @return IView
     */
    public function FindView(HttpContext $context, $viewName);
} 