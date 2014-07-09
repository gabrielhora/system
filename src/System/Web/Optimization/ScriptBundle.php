<?php
namespace System\Web\Optimization;

class ScriptBundle extends Bundle
{
	public function Render()
	{
		if (!file_exists($this->cachePath))
		{
			throw new \Exception("Cache path {$this->cachePath} does not exist.");
		}
		
		$hash = $this->Hash();
		$fileName = "$hash.js";
		$filePath = $this->cachePath.DIRECTORY_SEPARATOR.$fileName;
		
		if (!file_exists($filePath))
		{
			$contents = $this->Combine();
			file_put_contents($filePath, $contents);
		}

		return "<script type='text/javascript' src='{$this->cacheUrl}$fileName'></script>\n";
	}
}