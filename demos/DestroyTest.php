<?php

declare(strict_types=1);

/**
 * @package   phpunit-garbage-collector
 * @version   1.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

use PHPUnit\Framework\TestCase;

class DestroyTest extends TestCase
{
	public function intProvider(): iterable
	{
		for ($i = 0; $i < 10; $i++)
			yield [$i];
	}

	/**
	 * @dataProvider intProvider
	 */
	public function testInt(int $i): void
	{
		$this->assertIsInt($i);
	}

	public function __destruct()
	{
		echo "x\n";
	}
}

/*
 * SAMPLE OUTPUT
 *
 * PHPUnit 9.5.26 by Sebastian Bergmann and contributors.
 * 
 * Runtime:       PHP 8.1.1
 * Configuration: C:\...\phpunit.xml
 * Warning:       No code coverage driver available
 * 
 * ..........                                                        10 / 10 (100%)
 * 
 * Time: 00:00.024, Memory: 6.00 MB
 * 
 * OK (10 tests, 10 assertions)
 * x
 * x
 * x
 * x
 * x
 * x
 * x
 * x
 * x
 * x
 *
 * EXPECTED OUTPUT
 *
 * PHPUnit 9.5.26 by Sebastian Bergmann and contributors.
 * 
 * Runtime:       PHP 8.1.1
 * Configuration: C:\...\phpunit.xml
 * Warning:       No code coverage driver available
 * 
 * .x
 * .x
 * .x
 * .x
 * .x
 * .x
 * .x
 * .x
 * .x
 * .                                                        10 / 10 (100%)x
 * 
 * 
 * Time: 00:00.019, Memory: 6.00 MB
 * 
 * OK (10 tests, 10 assertions)
 */
