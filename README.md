# PHPUnit Garbage Collector

PHPUnit seems to waste a lot of memory because it instantiates a lot of objects before actually starting any test, meaning that the initial memory usage varies depending on the number of tests available.

Say, for example, that you have `UserTest` with 1k test methods. This will unfortunately result in 1k UserTest class instances before any of the tests are actually run. This is actually by design. In order to determine the number of tests, PHPUnit runs `@dataProvider` methods before actually running the tests (and the setUp method).

The problem comes once all of the tests in the test case have finished. Objects in memory are closer than they appear because they aren't dereferenced until after the entire test suite has finished. [Fortunately, this is on the radar](https://github.com/sebastianbergmann/phpunit/issues/4705).

Inspired by [Kris Wallsmith faster PHPUnit article](http://kriswallsmith.net/post/18029585104/faster-phpunit). The claim that this test listener that speeds up PHPUnit tests about 20% by freeing memory is dubious and probably is outdated now that [PHP 7 has rewritten `zval`s to be a bit more compact](https://www.npopov.com/2015/06/19/Internal-value-representation-in-PHP-7-part-2.html).

## Installation

To install this library, run the command below and you will get the latest version

```bash
composer require live627/phpunit-garbage-collector --dev
```

## Usage

Just add to your `phpunit.xml` configuration

```xml
<phpunit>
    <listeners>
        <listener class="\live627\PHPUnitGarbageCollector\MemoryGuard"/>
    </listeners>
</phpunit>
```

### Ignoring Tests

Sometimes it is necessary to ignore specific tests, where freeing their properties is undesired. For this use case, you have the ability to *extend the behaviour* of the listener by implementing the `IgnoreTestPolicy` interface.

As an example, if we hypothetically wanted to ignore all tests which include "Legacy" in their test filename, we could create a custom ignore policy as follows

```php
<?php

use live627\PHPUnitGarbageCollector\IgnoreTestPolicy;

class IgnoreLegacyTestPolicy implements IgnoreTestPolicy {
    public function shouldIgnore(\ReflectionObject $testReflection): bool {
        return strpos($testReflection->getFileName(), 'Legacy') !== false;
    }
}
```

And pass it to the constructor of our test listener in `phpunit.xml` configuration

```xml
<phpunit>
    <listeners>
        <listener class="\live627\PHPUnitGarbageCollector\MemoryGuard">
            <arguments>
                <object class="\IgnoreLegacyTestPolicy"/>
            </arguments>
        </listener>
    </listeners>
</phpunit>
```
