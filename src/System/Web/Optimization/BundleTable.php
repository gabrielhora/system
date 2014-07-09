<?php
namespace System\Web\Optimization;

class BundleTable
{
	/** @var BundleCollection */
	private static $bundles;
	
	public static function Bundles()
	{
		if (static::$bundles === null)
			static::$bundles = new BundleCollection();
		return static::$bundles;
	}
}