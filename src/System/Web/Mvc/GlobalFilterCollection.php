<?php
namespace System\Web\Mvc;

class GlobalFilterCollection
{
    /** @var IActionFilter[] */
    private $filters;

    public function __construct(array $filters = array())
    {
        $this->filters = $filters;
    }

    public function All()
    {
        return $this->filters;
    }

    public function Add(IActionFilter $filter, $index = null)
    {
        if ($index !== null)
            array_splice($this->filters, $index, 0, $filter);
        else
            $this->filters[] = $filter;
    }

    public function Clear()
    {
        $this->filters = array();
    }

    public function Contains(IActionFilter $filter)
    {
        return in_array($filter, $this->filters);
    }

    public function Remove(IActionFilter $filter)
    {
        for ($i = 0; $i < count($this->filters); $i++)
        {
            if ($this->filters[$i] === $filter)
            {
                unset($this->filters[$i]);
                return;
            }
        }
    }

    public function Count()
    {
        return count($this->filters);
    }
} 