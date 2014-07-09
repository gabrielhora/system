<?php
namespace System\Web\Optimization;

use System\Web\HttpContext;

abstract class Bundle
{
	/** @var HttpContext */
	protected $httpContext;
	
	/** @var string */
	protected $name;
	
	/** @var string[] */
	protected $files;
	
	/** @var string */
	protected $cachePath;
	
	/** @var string */
	protected $cacheUrl;

    /**
     * @param HttpContext $context
     * @param string $name
     * @param array $files
     */
    public function __construct(HttpContext $context, $name, array $files)
	{
		$this->httpContext = $context;
		$this->name = $name;
		foreach ($files as $file)
		{
			$this->files[] = $this->ExpandPath($file);
		}
	}
	
	public abstract function Render();
	
	public function SetCachePath($path)
	{
		$this->cachePath = $this->ExpandPath($path);
	}
	
	public function SetCacheUrl($url)
	{
		$this->cacheUrl = $url;
	}
	
	public function Name()
	{
		return $this->name;
	}
	
	public function Files()
	{
		return $this->files;
	}
	
	public function Add(/* ...$files */)
	{
		$files = func_get_args();
		foreach ($files as $file)
		{
			$this->files[] = $this->ExpandPath($file);
		}
	}
	
	protected function Hash()
	{
		$lastModified = 0;
		foreach ($this->files as $file)
		{
			if (file_exists($file))
				$lastModified = max($lastModified, filemtime($file));
		}
		return md5($lastModified . implode($this->files));
	}
	
	protected function Combine()
	{
		$contents = '';
		foreach ($this->files as $file)
		{
			// get the "min" version of the file name (eg. application.css -> application.min.css)
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$dirname = pathinfo($file, PATHINFO_DIRNAME);
			$filename = pathinfo($file, PATHINFO_FILENAME);
			$minFileName = "$dirname/$filename.min.$extension";

			if (file_exists($minFileName))
				$contents .= file_get_contents($minFileName)."\n\n";
			else if (file_exists($file))
				$contents .= file_get_contents($file)."\n\n";
		}
		return $contents;
	}
	
	protected function ExpandPath($virtualPath)
	{
		$basePath = $this->httpContext->BasePath();
        if (strpos($virtualPath, '~') === 0)
            $path = preg_replace('/^~/', $basePath, $virtualPath);
        else
            $path = $basePath . '/' . ltrim($virtualPath, '/');
        return file_exists($path) ? realpath($path) : $path;
	}
}