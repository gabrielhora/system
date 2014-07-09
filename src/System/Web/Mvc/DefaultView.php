<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;
use System\Web\Optimization\Bundle;
use System\Web\Optimization\BundleTable;
use System\Web\Optimization\ScriptBundle;
use System\Web\Optimization\StyleBundle;

class DefaultView implements IView
{
    /** @var string */
    private $viewPath;

    /** @var string */
    private $body;

    /** @var HttpContext */
    private $httpContext;

    /** @var array */
    private $sections;

    /** @var string */
    private $currentSection;

    /** @var string */
    private $Layout;

    /** @var object */
    private $Model;

    /** @var array */
    private $ViewBag;

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
        $this->ViewBag = new \stdClass();
    }

    /**
     * @param $name
     * @return Bundle
     */
	public function RenderBundle($name)
	{
		$bundle = BundleTable::Bundles()->Get($name);
		return $bundle !== null ? $bundle->Render() : '';
	}
	
    /**
     * Return the section
     * @param string $name
     * @return string
     */
    public function RenderSection($name)
    {
        return isset($this->sections[$name]) ? $this->sections[$name] : '';
    }

    /**
     * Start a new section
     * @param string $name
     */
    public function BeginSection($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    /**
     * End the current section
     */
    public function EndSection()
    {
        if ($this->currentSection !== null)
        {
            $section = ob_get_clean();
            $this->sections[$this->currentSection] = $section;
        }
        $this->currentSection = null;
    }

    /**
     * @return string
     */
    public function RenderBody()
    {
        return $this->body;
    }

    /**
     * @param $partialName
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function RenderPartial($partialName, array $data = array())
    {
        $view = ViewEngines::Engines()->FindPartialView($this->httpContext, $partialName);
        if ($view === null) throw new \Exception("Could not find partial view $partialName");
        return $view->Render($this->httpContext, $data);
    }

    /**
     * @param HttpContext $context
     * @param object $model
     * @return string
     * @throws \Exception
     */
    public function Render(HttpContext $context, $model = null)
    {
        $this->httpContext = $context;
        $this->Model = $model;

        // convert the data array to the model std class
        if (is_array($model))
        {
            $this->Model = new \stdClass();
            foreach ($model as $key => $value) { $this->Model->$key = $value; }
        }

        // render the view
        ob_start();
        try
        {
            require $this->viewPath;
        }
        catch (\Exception $e)
        {
            ob_end_clean();
            throw $e;
        }
        $this->body = ob_get_clean();

        if ($this->Layout !== null)
        {
            // Can't use FindView here because the layout and the view need to share ViewBag data
            $layoutPath = ViewEngines::Engines()->FileExists($context, $this->Layout);
            if ($layoutPath === false) throw new \Exception("Could not find layout {$this->Layout}");

            ob_start();
            try
            {
                require $layoutPath;
            }
            catch (\Exception $e)
            {
                ob_end_clean();
                throw $e;
            }
            return ob_get_clean();
        }

        return $this->body;
    }
}