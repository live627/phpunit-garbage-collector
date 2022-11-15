<?php

namespace live627\PHPUnitGarbageCollector;

use ReflectionObject;

interface IgnoreTestPolicy
{
	public function shouldIgnore(ReflectionObject $testReflection): bool;
}
