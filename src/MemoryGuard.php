<?php

declare(strict_types=1);

/**
 * @package   phpunit-garbage-collector
 * @version   2.0.2
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\PHPUnitGarbageCollector;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use ReflectionObject;

class MemoryGuard implements TestListener
{
	use TestListenerDefaultImplementation;

	private const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit';
	private IgnoreTestPolicy $ignorePolicy;

	public function __construct(IgnoreTestPolicy $ignorePolicy = null)
	{
		$this->ignorePolicy = $ignorePolicy ?: new NeverIgnoreTestPolicy;
	}

	public function endTest(Test $test, float $time): void
	{
		$testReflection = new ReflectionObject($test);

		if ($this->ignorePolicy->shouldIgnore($testReflection))
			return;

			foreach ($testReflection->getProperties() as $prop)
			{
				if ($prop->isStatic() || strpos($prop->getDeclaringClass()->getName(), 'PHPUnit\\') === 0)
					continue;

				unset($test->{$prop->getName()});
		}
	}
}

class NeverIgnoreTestPolicy implements IgnoreTestPolicy
{
	public function shouldIgnore(ReflectionObject $testReflection): bool
	{
		return false;
	}
}
