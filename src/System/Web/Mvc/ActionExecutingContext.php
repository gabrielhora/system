<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ActionExecutingContext
{
    /** @var HttpContext */
    private $httpContext;

    /** @var ActionResult */
    private $result;

    /** @var array */
    private $actionParameters;

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
    public function SetResult(ActionResult $result)
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

    /**
     * @param array $actionParameters
     */
    public function SetActionParameters($actionParameters)
    {
        $this->actionParameters = $actionParameters;
    }

    /**
     * @return array
     */
    public function ActionParameters()
    {
        return $this->actionParameters;
    }

    public function __construct(HttpContext $context, array $actionParameters = array())
    {
        $this->SetHttpContext($context);
        $this->SetActionParameters($actionParameters);
    }
} 