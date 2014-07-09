<?php
namespace System\Web\Routing;

use System\Web\IHttpHandler;

class Route
{
    /** @var string */
    private $url;

    /** @var RouteComponent[] */
    private $components;

    /** @var IHttpHandler */
    private $handler;

    /** @var array */
    private $defaults;

    /** @var array */
    private $constraints;

	public function __construct($url, IHttpHandler $handler, array $defaults = array(), array $constraints = array())
	{
        $this->url = '/'.ltrim(rtrim($url, '/'), '/').'/?';
        $this->handler = $handler;
        $this->defaults = $defaults === null ? array() : $defaults;
        $this->constraints = $constraints === null ? array() : $constraints;
        $this->components = $this->ExtractComponents($url);
    }

    /**
     * @return string
     */
    public function Url()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function Defaults()
    {
        return $this->defaults;
    }

    /**
     * @return array
     */
    public function Constraints()
    {
        return $this->constraints;
    }

    /**
     * @return IHttpHandler
     */
    public function Handler()
    {
        return $this->handler;
    }

    public function Match($uri)
    {
        if (preg_match($this->BuildRegex(), $uri, $matches) === 1)
        {
            $args = $this->defaults;
            array_shift($matches);
            for ($i = 0; $i < count($matches); $i++)
            {
                // check each component constraints
                $component = $this->components[$i];
                $match = $matches[$i];
                if (preg_match("#{$component->Constraint()}#", $match) === 0)
                    return false; // no match
                $args[$component->Name()] = $match;
            }
            return $args;
        }
        return false;
    }

    private function ExtractComponents($url)
    {
        $components = array();
        if (preg_match_all('#\{(.*?)\}#', $url, $matches)) // match everything between {}
        {
            foreach ($matches[0] as $match)
            {
                $isOptional = substr($match, -2) === '?}';
                $name = $isOptional ? substr($match, 1, -2) : substr($match, 1, -1);
                $constraint = array_key_exists($name, $this->constraints) ? $this->constraints[$name] : null;
                $defaultValue = array_key_exists($name, $this->defaults) ? $this->defaults[$name] : null;
                $components[] = new RouteComponent($name, $defaultValue, $constraint, $isOptional);
            }
        }
        return $components;
    }

    public function BuildRegex()
    {
        $regex = '#^'.str_replace('/{', '/?{', $this->Url()).'$#';
        foreach ($this->components as $component)
            $regex = str_replace($component->Descriptor(), $component->Regex(), $regex);
        return $regex;
    }

    public function BuildUrl(array $componentsValues = array())
    {
        $url = $this->Url();
        foreach ($this->components as $component)
        {
            $name = $component->Name();
            $value = array_key_exists($name, $componentsValues) ? $componentsValues[$name] : null;
            if ($value === null)
            {
                if ($component->Optional())
                    $url = preg_replace('#'.preg_quote($component->Descriptor()).'/?#', '', $url, 1);
                else
                    return false;
            }
            else
            {
                $url = str_replace($component->Descriptor(), $value, $url);
            }
        }
        return $url;
    }

    public function Component($name)
    {
        foreach ($this->components as $component)
        {
            if ($component->Name() === $name)
                return $component;
        }
        return null;
    }
}