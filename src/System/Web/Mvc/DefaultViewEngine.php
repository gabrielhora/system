<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class DefaultViewEngine extends VirtualPathProviderViewEngine
{
    public function __construct()
    {
        $this->SetViewLocationFormats(array(
            '~/Views/%2$s/%1$s.php',
            '~/Views/Shared/%2$s/%1$s.php',
            '~/Views/Shared/%1$s.php'
        ));

        $this->SetPartialViewLocationFormats(array(
            '~/Views/%2$s/_%1$s.php',
            '~/Views/Shared/%2$s/_%1$s.php',
            '~/Views/Shared/_%1$s.php'
        ));
    }

    /**
     * @param HttpContext $context
     * @param string $viewPath
     * @return IView
     */
    public function CreateView(HttpContext $context, $viewPath)
    {
        return new DefaultView($viewPath);
    }

    /**
     * @param HttpContext $context
     * @param string $viewPath
     * @return IView
     */
    public function CreatePartialView(HttpContext $context, $viewPath)
    {
        return new DefaultView($viewPath);
    }
}