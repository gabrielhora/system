<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;
use System\Web\IHttpHandler;

class MvcHandler implements IHttpHandler
{
    protected $controllerFactory;

	public function __construct(IControllerFactory $controllerFactory)
	{
        $this->controllerFactory = $controllerFactory;
	}

	public function ProcessRequest(HttpContext $context)
	{
        $routeData = $context->RouteData();
        $controller = $this->controllerFactory->CreateController($context, $routeData->ControllerName());
        $actionResult = $controller->Execute($context);

        if (!($actionResult instanceof ActionResult))
            throw new \Exception("The action did not return an ActionResult");

        // OnResultExecutingFilters
        $resultExecutingContext = new ResultExecutingContext($context, $actionResult);
        foreach (GlobalFilters::Filters()->All() as $filter)
        {
            $filter->OnResultExecuting($resultExecutingContext);
            $actionResult = $resultExecutingContext->Result();
        }

        $resultExecutedContext = new ResultExecutedContext($context, $actionResult);

        // execute the resulting ActionResult
        try
        {
            $actionResult->ExecuteResult($context, $controller);
        }
        catch (\Exception $e)
        {
            $resultExecutedContext->SetException($e);
        }

        $resultExecutedContext->SetResult($actionResult);

        // OnResultExecutedFilters
        foreach (GlobalFilters::Filters()->All() as $filter)
        {
            $filter->OnResultExecuted($resultExecutedContext);
        }
	}
}