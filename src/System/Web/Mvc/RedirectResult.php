<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class RedirectResult extends ActionResult
{
    private $url;
    private $permanent;

    /**
     * @param string $url
     * @param bool $permanent
     */
    public function __construct($url, $permanent = false)
    {
        $this->url = $url;
        $this->permanent = $permanent;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $context->Response()->SetStatusCode($this->permanent ? 301 : 302);
        $context->Response()->Headers()->Add('Location', $this->url);
    }
}