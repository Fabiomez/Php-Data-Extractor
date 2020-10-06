<?php

namespace Tests\ValueGetters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Fmezini\DataExtractor\ValueGetters\SubstringValueGetter;

class SubstringValueGetterTest extends TestCase
{
    public function testGetValueShouldThrowInvalidArgumentExceptionWhenSourceIsNotString()
	{
		$schema = [];
		$source = 123;

		$extractor = new SubstringValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected a string. Got: integer');

		$extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenNoStartIsFound()
	{
        $schema = [];
        $source = 'Any string';

        $extractor = new SubstringValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the key "start" to exist.');

        $extractor->getValue($schema, $source);
    }

	public function testGetValueShouldThrowInvalidArgumentExceptionWhenStartIsNotInt()
	{
		$schema = ['start' => 'Invalid int value'];
		$source = 'Any string';

		$extractor = new SubstringValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected an integer. Got: string');

		$extractor->getValue($schema, $source);
	}

	public function testGetValueShouldThrowInvalidArgumentExceptionWhenNoLengthIsFound()
	{
		$schema = ['start' => 0];
		$source = 'Any string';

		$extractor = new SubstringValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected the key "length" to exist');

		$extractor->getValue($schema, $source);
	}

	public function testGetValueShouldThrowInvalidArgumentExceptionWhenLengthIsNotInt()
	{
		$schema = ['start' => 0, 'length' => 'Invalid int value'];
		$source = 'Any string';

		$extractor = new SubstringValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected an integer. Got: string');

		$extractor->getValue($schema, $source);
	}

	public function testGetValueExtractingFunction()
	{
		$source = '1st String2nd String';

		$extractor = new SubstringValueGetter();

		$schema = ['start' => 0, 'length' => 10];
		$firstString = $extractor->getValue($schema, $source);

		$schema = ['start' => 10, 'length' => 10];
		$secondString = $extractor->getValue($schema, $source);

		$this->assertEquals('1st String', $firstString);
		$this->assertEquals('2nd String', $secondString);
	}
}
