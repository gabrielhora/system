<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ContentResult extends ActionResult
{
    /** @var string */
    private $content;

    /** @var string */
    private $contentType;

    public function __construct($content, $contentType = 'text/html')
    {
        $this->content = $content;
        $this->contentType = $contentType;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $context->Response()->Write($this->content);
        $context->Response()->SetContentType($this->contentType);
    }
}