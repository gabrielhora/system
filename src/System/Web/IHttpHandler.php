<?php
namespace System\Web;

interface IHttpHandler
{
	function ProcessRequest(HttpContext $context);
}