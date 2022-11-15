<?php

declare(strict_types=1);

/**
 * @package   phpunit-garbage-collector
 * @version   2.0.2
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

use live627\PHPUnitGarbageCollector\{IgnoreTestPolicy, MemoryGuard};
use PHPUnit\Framework\TestCase;

class MemoryGuardTest extends TestCase
{
	private DummyTest $dummyTest;

	protected function setUp(): void
	{
		$this->dummyTest = new DummyTest;
	}

	public function testShouldFreeTestProperty(): void
	{
		$this->assertObjectHasAttribute('property', $this->dummyTest);
		$this->endTest(new MemoryGuard);

		// assertObjectNotHasAttribute() seems to not work...
		$this->assertFalse(isset($this->dummyTest->property));
	}

	private function endTest(MemoryGuard $listener): void
	{
		$listener->endTest($this->dummyTest, 0);
	}

	public function testShouldNotFreeTestPropertyWithIgnoreAlwaysPolicy(): void
	{
		$this->endTest(new MemoryGuard(new AlwaysIgnoreTestPolicy));

		$this->assertObjectHasAttribute('property', $this->dummyTest);
		$this->assertNotNull($this->dummyTest->property);
	}
}

class DummyTest extends TestCase
{
	public $property = 1;
}

class AlwaysIgnoreTestPolicy implements IgnoreTestPolicy
{
	public function shouldIgnore(\ReflectionObject $testReflection): bool
	{
		return true;
	}
}
