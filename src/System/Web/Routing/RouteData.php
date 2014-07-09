<?php
namespace System\Web\Routing;

use System\Collections\NameValueCollection;

class RouteData
{
    /** @var Route */
    private $route;

    /** @var NameValueCollection */
    private $values;

    /** @var string */
    private $namespace;

    /**
     * @param Route $route
     */
    public function SetRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return Route
     */
    public function Route()
    {
        return $this->route;
    }

    /**
     * @param array $values
     */
    public function SetValues(array $values)
    {
        $this->values = new NameValueCollection($values);
    }

    /**
     * @return NameValueCollection
     */
    public function Values()
    {
        return $this->values;
    }

    /**
     * @param string $value
     */
    public function SetControllerNamespace($value)
    {
        $this->namespace = $value;
    }

    /**
     * @return string
     */
    public function ControllerNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string|null
     */
    public function ControllerName()
    {
        if ($this->values !== null)
        {
            return ucfirst($this->values->Get('controller'));
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function ActionName()
    {
        if ($this->values !== null)
        {
            return ucfirst($this->values->Get('action'));
        }
        return null;
    }
}