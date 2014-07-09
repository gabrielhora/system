<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class FileResult extends ActionResult
{
    private $bytes;
    private $contentType;
    private $fileName;

    /**
     * @param array $bytes
     * @param string $contentType
     * @param string $fileName
     */
    public function __construct($bytes, $contentType, $fileName)
    {
        $this->bytes = $bytes;
        $this->contentType = $contentType;
        $this->fileName = $fileName;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $context->Response()->SetContentType($this->contentType);
        $context->Response()->Headers()->Add('Content-Disposition', "attachment; filename=\"{$this->fileName}\"");
        $context->Response()->Write($this->bytes);
    }
}