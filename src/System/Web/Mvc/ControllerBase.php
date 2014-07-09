<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;
use System\Web\Routing\RouteData;

abstract class ControllerBase
{
    /** @var RouteData */
    private $routeData;

    /** @var HttpContext */
    private $httpContext;

    public function HttpContext()
    {
        return $this->httpContext;
    }

    public function RouteData()
    {
        return $this->routeData;
    }

    public function Filters()
    {
        return GlobalFilters::Filters();
    }

    /**
     * Execute the current request
     * @param HttpContext $context
     * @return \System\Web\Mvc\ActionResult
     */
    public function Execute(HttpContext $context)
    {
        $this->httpContext = $context;
        $this->routeData = $context->RouteData();
        $actionName = ucfirst($this->routeData->ActionName());
        $actionResult = null;
        $actionParameters = $this->ActionParameters($actionName);

        // OnActionExecutingFilters
        $actionExecutingContext = new ActionExecutingContext($context, $actionParameters);
        foreach (GlobalFilters::Filters()->All() as $filter)
        {
            $filter->OnActionExecuting($actionExecutingContext);
            if (($actionResult = $actionExecutingContext->Result()) !== null) break;
        }

        // Run the action if no ActionResult was generated in the filters
        if ($actionResult === null)
        {
            $actionExecutedContext = new ActionExecutedContext($context, $actionParameters);

            try
            {
                $actionResult = $this->InvokeAction($actionName, $actionParameters);
                if ($actionResult !== null)
                    $actionExecutedContext->SetResult($actionResult);
                else
                    throw new \Exception("{$this->routeData->ControllerName()}::{$actionName} action did not return an instance of System\\Web\\Mvc\\ActionResult.");
            }
            catch (\Exception $e)
            {
                $actionExecutedContext->SetException($e);
            }

            // OnActionExecutedFilters
            foreach (GlobalFilters::Filters()->All() as $filter)
            {
                $filter->OnActionExecuted($actionExecutedContext);
                $actionResult = $actionExecutedContext->Result();
            }
        }

        return $actionResult;
    }

    /**
     * @param $actionName
     * @return array
     */
    private function ActionParameters($actionName)
    {
        $method = new \ReflectionMethod(get_class($this), $actionName);
        $params = array();
        foreach ($method->getParameters() as $param)
        {
            $paramName = $param->getName();

            // try to find the parameter value in the route data first
            $value = $this->routeData->Values()->Get($paramName);
            if ($value !== null)
            {
                $params[$paramName] = $value;
                continue;
            }

            // now find in post data
            $value = $this->httpContext->Request()->Form()->Get($paramName);
            if ($value !== null)
            {
                $params[$paramName] = $value;
                continue;
            }

            // lastly, try the querystring data
            $value = $this->httpContext->Request()->QueryString()->Get($paramName);
            if ($value !== null)
            {
                $params[$paramName] = $value;
                continue;
            }
        }
        return $params;
    }

    /**
     * @param string $actionName
     * @param array $actionParameters
     * @return \System\Web\Mvc\ActionResult
     */
    private function InvokeAction($actionName, array $actionParameters = array())
    {
        $method = new \ReflectionMethod(get_class($this), $actionName);
        return $method->invokeArgs($this, $actionParameters);
    }
} 