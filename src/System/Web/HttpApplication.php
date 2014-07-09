<?php
namespace System\Web;

use System\Web\Mvc\ViewEngines;
use System\Web\Routing\RouteTable;

class HttpApplication
{
	private $httpContext;
	
    public function __construct()
    {
        $class = new \ReflectionClass($this);
		$this->httpContext = HttpContext::Current();
        $this->httpContext->SetBasePath(dirname($class->getFileName()));

        // setup some PHP stuff
        set_error_handler(array($this, 'ErrorHandler'));
    }
	
	public function HttpContext()
	{
		return $this->httpContext;
	}

    /**
     * Override this method
     */
    protected function Application_BeginRequest() { }

    /**
     * Override this method
     */
    protected function Application_EndRequest() { }

    /**
     * Run before every request
     */
    private function OnApplicationBeginRequest()
    {
        $this->Application_BeginRequest();
    }

    /**
     * Run after every request
     */
    private function OnApplicationEndRequest()
    {
        $this->Application_EndRequest();
    }

    /**
     * This is where it all starts
     */
    public function Start()
	{
		$this->OnApplicationBeginRequest();

        // find the route to execute
        $ctx = HttpContext::Current();
        $uri = $ctx->Request()->Uri();
        $routeData = RouteTable::Match($uri);

        if ($routeData !== false)
        {
            $ctx->SetRouteData($routeData);
            $routeData->Route()->Handler()->ProcessRequest($ctx);
            $ctx->Response()->End();
        }
        else
        {
            $this->HandleNotFound($ctx);
        }

		$this->OnApplicationEndRequest();
	}

    private function HandleNotFound(HttpContext $context)
    {
        $view = ViewEngines::Engines()->FindView($context, '404');
        $view = $view ?: ViewEngines::Engines()->FindView($context, 'Error');

        $context->Response()->SetStatusCode(404);
        $context->Response()->Write($view !== null
                ? $view->Render($context, new \Exception('Not Found', 404))
                : "<pre><strong>404</strong> Not Found</pre>");
        $context->Response()->End();
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws \ErrorException
     */
    public function ErrorHandler($errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}