<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ResultExecutingContext
{
    /** @var HttpContext */
    private $httpContext;

    /** @var ActionResult */
    private $result;

    /**
     * @param \System\Web\HttpContext $httpContext
     */
    public function SetHttpContext($httpContext)
    {
        $this->httpContext = $httpContext;
    }

    /**
     * @return \System\Web\HttpContext
     */
    public function HttpContext()
    {
        return $this->httpContext;
    }

    /**
     * @param \System\Web\Mvc\ActionResult $result
     */
    public function SetResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \System\Web\Mvc\ActionResult
     */
    public function Result()
    {
        return $this->result;
    }

    public function __construct(HttpContext $context, ActionResult $result)
    {
        $this->SetHttpContext($context);
        $this->SetResult($result);
    }
}