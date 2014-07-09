<?php
namespace System\Web;

use System\Collections\NameValueCollection;

class HttpResponse
{
    /** @var string */
    private $body;

    /** @var NameValueCollection */
    private $headers;

    /** @var int */
    private $status;

    public function __construct()
    {
        $this->body = '';
        $this->headers = new NameValueCollection(array('Content-Type' => 'text/html; charset=utf-8'));
        $this->status = 200;
    }

    public function Clear()
    {
        $this->body = '';
    }

    public function Write($content)
    {
        $this->body .= $content;
    }

    public function Length()
    {
        return strlen($this->body);
    }

    public function Headers()
    {
        return $this->headers;
    }

    public function StatusCode()
    {
        return $this->status;
    }

    public function SetStatusCode($status)
    {
        $this->status = $status;
    }

    public function SetContentType($contentType)
    {
        $this->headers->Set('Content-Type', $contentType);
    }

    public function ContentType()
    {
        $contentType = $this->headers->Get('Content-Type');
        return substr($contentType, 0, strpos($contentType, ';'));
    }

    public function SetCharset($charset)
    {
        $contentType = $this->ContentType();
        $this->headers->Set('Content-Type', "$contentType; charset=$charset");
    }

    public function Charset()
    {
        $contentType = $this->headers->Get('Content-Type');
        return substr($contentType, strpos($contentType, '=') + 1);
    }

    /**
     * Send the response back to the client
     */
    public function End()
    {
        header(' ', true, $this->status);
        foreach ($this->headers->AllKeys() as $key)
        {
            header("$key: " . $this->headers->Get($key));
        }
        echo $this->body;
    }
} 