<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class ResultExecutedContext extends ResultExecutingContext
{
    /** @var \Exception */
    private $exception;

    /** @var bool */
    private $exceptionHandled;

    /**
     * @param \Exception $exception
     */
    public function SetException($exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function Exception()
    {
        return $this->exception;
    }

    /**
     * @param boolean $exceptionHandled
     */
    public function SetExceptionHandled($exceptionHandled)
    {
        $this->exceptionHandled = $exceptionHandled;
    }

    /**
     * @return boolean
     */
    public function ExceptionHandled()
    {
        return $this->exceptionHandled;
    }

    public function __construct(HttpContext $context, ActionResult $result, \Exception $exception = null)
    {
        parent::__construct($context, $result);
        $this->SetException($exception);
    }
}