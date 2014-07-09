<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class HandleErrorFilter implements IActionFilter
{
    public function OnActionExecuting(ActionExecutingContext $context)
    {
    }

    public function OnActionExecuted(ActionExecutedContext $context)
    {
        if ($context->Exception() !== null && !$context->ExceptionHandled())
        {
            $this->HandleException($context->Exception(), $context->HttpContext());
            $context->SetExceptionHandled(true);
            $context->SetResult(new EmptyResult());
        }
    }

    public function OnResultExecuting(ResultExecutingContext $context)
    {
    }

    public function OnResultExecuted(ResultExecutedContext $context)
    {
        if ($context->Exception() !== null && !$context->ExceptionHandled())
        {
            $this->HandleException($context->Exception(), $context->HttpContext());
            $context->SetExceptionHandled(true);
            $context->SetResult(new EmptyResult());
        }
    }

    private function HandleException(\Exception $exception, HttpContext $context)
    {
        $statusCode = intval($exception->getCode()) !== 0 ? intval($exception->getCode()) : 500;

        // try to find a view with the corresponding status code, or an 'Error' view
        $view = ViewEngines::Engines()->FindView($context, $statusCode);
        $view = $view ?: ViewEngines::Engines()->FindView($context, 'Error');

        $response = $view === null
            ? $this->DefaultErrorTemplate($exception, $context->Request()->Uri())
            : $view->Render($context, $exception);

        $context->Response()->SetStatusCode($statusCode);
        $context->Response()->Clear();
        $context->Response()->Write($response);
    }

    private function DefaultErrorTemplate(\Exception $exception, $url)
    {
        $type = get_class($exception);
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $stackTrace = $exception->getTraceAsString();

        $defaultErrorTemplate = <<<EOT
            <!DOCTYPE html>
            <html>
                <head>
                    <title>$type</title>
                    <meta name="viewport" content="width=device-width" />
                    <style>
                     body {font-family:"Verdana";font-weight:normal;font-size: .7em;color:black;}
                     p {font-family:"Verdana";font-weight:normal;color:black;margin-top: -5px}
                     b {font-family:"Verdana";font-weight:bold;color:black;margin-top: -5px}
                     H1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
                     H2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
                     pre {font-family:"Consolas","Lucida Console",Monospace;font-size:11pt;margin:0;padding:0.5em;line-height:14pt}
                     .marker {font-weight: bold; color: black;text-decoration: none;}
                     .version {color: gray;}
                     .error {margin-bottom: 10px;}
                     .expandable { text-decoration:underline; font-weight:bold; color:navy; cursor:hand; }
                     @media screen and (max-width: 639px) {
                      pre { width: 440px; overflow: auto; white-space: pre-wrap; word-wrap: break-word; }
                     }
                     @media screen and (max-width: 479px) {
                      pre { width: 280px; }
                     }
                    </style>
                </head>
                <body bgcolor="white">
                    <span><H1>Server Error in '$url'.<hr width=100% size=1 color=silver></H1>
                    <h2> <i>$message</i> </h2></span>
                    <font face="Arial, Helvetica, Geneva, SunSans-Regular, sans-serif ">
                    <b> Exception Details: </b> $type
                    <br><br>
                    <b> Source File: </b>$file<b> &nbsp;&nbsp; Line: </b> $line
                    <br><br>
                    <b>Stack Trace:</b> <br><br>
                    <table width=100% bgcolor="#ffffcc">
                       <tr>
                          <td>
                              <code><pre>
$stackTrace
                              </pre></code>
                          </td>
                       </tr>
                    </table>
                    <br>
                    <hr width=100% size=1 color=silver>
                    </font>
                </body>
            </html>
EOT;
        return $defaultErrorTemplate;
    }
}