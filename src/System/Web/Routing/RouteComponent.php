<?php
namespace System\Web\Routing;

class RouteComponent
{
    private $name;
    private $defaultValue;
    private $constraint;
    private $optional;
    private $regex;
    private $descriptor;

    public function __construct($name, $defaultValue = null, $constraint = null, $optional = false)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->constraint = $constraint !== null ? $constraint : '[-\\w]+';
        $this->optional = $optional;
        $this->descriptor = '{'.$this->name . ($optional ? '?}' : '}');
        $this->regex = '('.($this->defaultValue !== null ? $this->defaultValue.'|' : '').'[-\\w]+)'
            .($optional || $this->defaultValue !== null ? '?' : '');
    }

    public function SetConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    public function Constraint()
    {
        return $this->constraint;
    }

    public function SetDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function DefaultValue()
    {
        return $this->defaultValue;
    }

    public function SetName($name)
    {
        $this->name = $name;
    }

    public function Name()
    {
        return $this->name;
    }

    public function SetOptional($optional)
    {
        $this->optional = $optional;
    }

    public function Optional()
    {
        return $this->optional;
    }

    public function Regex()
    {
        return $this->regex;
    }

    public function Descriptor()
    {
        return $this->descriptor;
    }
}