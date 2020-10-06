<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Fmezini\DataExtractor\Cacher;

class CacherTest extends TestCase
{
	public function testCacheBehavior()
	{
		$cacher = new Cacher();

		$cacher->put('aKey', 'aValue');

		$this->assertEquals('aValue', $cacher->get('aKey'));
		$this->assertTrue($cacher->has('aKey'));

		$cacher->del('aKey');
		$this->assertFalse($cacher->has('aKey'));
	}
}
