<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class JsonResult extends ActionResult
{
    private $data;
    private $contentType;

    public function __construct($data, $contentType = 'application/json')
    {
        $this->data = $data;
        $this->contentType = $contentType;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $context->Response()->SetContentType($this->contentType);
        $context->Response()->Write(json_encode($this->data));
    }
}