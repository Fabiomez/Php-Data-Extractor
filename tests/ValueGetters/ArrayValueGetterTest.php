<?php

namespace Tests\ValueGetters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Fabiomez\DataExtractor\ValueGetters\ArrayValueGetter;

class ArrayValueGetterTest extends TestCase
{
    public function testGetValueShouldThrowInvalidArgumentExceptionWhenNoIndexIsFound()
	{
		$schema = [];
		$source = [];

		$extractor = new ArrayValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected the key "index" to exist.');

		$extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenIndexIsNotFoundInSource()
	{
		$schema = ['index' => 3];
		$source = ['First', 'Seccond'];

		$extractor = new ArrayValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected the key 3 to exist.');

		$extractor->getValue($schema, $source);
    }

    public function testGetValueShouldReturnTheSourceIndexValue()
	{
		$schema = ['index' => 1];
		$source = ['First', 'Second'];

		$extractor = new ArrayValueGetter();

        $value = $extractor->getValue($schema, $source);
        
        $this->assertEquals('Second', $value);
    }
}
