<?php
namespace System\Web\Optimization;

class BundleCollection
{
	/** @var Bundle[] */
	private $bundles = array();
	
	/** @var string */
	private $cachePath = '~/../public/cache';
	
	/** @var string */
	private $cacheUrl = '/cache/';
	
	public function SetCachePath($path)
	{
		$this->cachePath = $path;
	}
	
	public function SetCacheUrl($url)
	{
		$this->cacheUrl = $url;
	}
	
	public function Get($name)
    {
        return array_key_exists($name, $this->bundles) ? $this->bundles[$name] : null;
    }

	public function Add(/*... Bundle $bundles*/)
	{
        $bundles = func_get_args();
        foreach ($bundles as $bundle)
        {
            $bundle->SetCachePath($this->cachePath);
            $bundle->SetCacheUrl($this->cacheUrl);
            $this->bundles[$bundle->Name()] = $bundle;
        }
	}
}