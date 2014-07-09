<?php
namespace System\Web\Optimization;

class StyleBundle extends Bundle
{
	public function Render()
	{
		if (!file_exists($this->cachePath))
		{
			throw new \Exception("Cache path {$this->cachePath} does not exist.");
		}
		
		$hash = $this->Hash();
		$fileName = "$hash.css";
		$filePath = $this->cachePath.DIRECTORY_SEPARATOR.$fileName;
		
		if (!file_exists($filePath))
		{
			$contents = $this->Combine();
			file_put_contents($filePath, $contents);
		}
	
		return "<link rel='stylesheet' type='text/css' href='{$this->cacheUrl}$fileName' media='screen'>\n";
	}
}