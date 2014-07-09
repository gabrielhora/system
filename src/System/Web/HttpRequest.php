<?php
namespace System\Web;

use System\Collections\NameValueCollection;

class HttpRequest
{
    private static $headers;
    private static $form;
    private static $queryString;
    private static $files;

    /**
     * @return NameValueCollection
     */
    public function Form()
    {
        if (static::$form === null)
            static::$form = new NameValueCollection($_POST);
        return static::$form;
    }

    /**
     * @return NameValueCollection
     */
    public function QueryString()
    {
        if (static::$queryString === null)
            static::$queryString = new NameValueCollection($_GET);
        return static::$queryString;
    }

    /**
     * @return NameValueCollection
     */
    public function Files()
    {
        if (static::$files === null)
            static::$files = new NameValueCollection($_FILES);
        return static::$files;
    }

    /**
     * @return NameValueCollection
     */
    public function Headers()
    {
        if (static::$headers === null)
            static::$headers = new NameValueCollection($_SERVER);
        return static::$headers;
    }

    /**
     * @return string
     */
    public function HostName()
    {
        if ($this->Headers()->Get('HTTP_HOST'))
        {
            $host = explode(':', $this->Headers()->Get('HTTP_HOST'));
            return $host[0];
        }
        return $this->Headers()->Get('SERVER_NAME');
    }

    /**
     * @return string
     */
    public function ScriptName()
    {
        if (strpos($this->Headers()->Get('REQUEST_URI'), $this->Headers()->Get('SCRIPT_NAME')) === 0)
        {
            return $this->Headers()->Get('SCRIPT_NAME');
        }
        else
        {
            return str_replace('\\', '/', dirname($this->Headers()->Get('SCRIPT_NAME')));
        }
    }

    /**
     * @return string
     */
    public function Uri()
    {
        $pathInfo = substr_replace($this->Headers()->Get('REQUEST_URI'), '', 0, strlen($this->ScriptName()));
        if (strpos($pathInfo, '?') !== false)
        {
            $pathInfo = substr_replace($pathInfo, '', strpos($pathInfo, '?'));
        }
        return '/'.ltrim($pathInfo, '/');
    }

    /**
     * @return string
     */
    public function Method()
    {
        return ($this->Form()->Get('_method') !== null
            && ($_method = strtoupper($this->Form()->Get('_method'))) && in_array($_method, array('PUT', 'DELETE')))
            ? $_method : $this->Headers()->Get('REQUEST_METHOD');
    }
}