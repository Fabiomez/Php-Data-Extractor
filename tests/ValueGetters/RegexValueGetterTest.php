<?php

namespace Tests\ValueGetters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Fabiomez\DataExtractor\ValueGetters\RegexValueGetter;

class RegexValueGetterTest extends TestCase
{
    public function testGetValueShouldThrowInvalidArgumentExceptionWhenSourceIsNotString()
	{
		$schema = [];
		$source = 123;

		$extractor = new RegexValueGetter();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected a string. Got: integer');

		$extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenNoPatternIsFound()
	{
        $schema = [];
        $source = 'Any string';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the key "pattern" to exist.');

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenPatternIsNotString()
	{
        $schema = ['pattern' => 123];
        $source = 'Any string';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a string. Got: integer');

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenNoIndexIsFound()
	{
        $schema = ['pattern' => 'A string pattern'];
        $source = 'Any string';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected the key "index" to exist.');

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenIndexIsNotInt()
	{
        $schema = ['pattern' => 'A string pattern', 'index' => 'invalid'];
        $source = 'Any string';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an integerish value. Got: string');

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenPatternDoesNotMatch()
	{
        $schema = ['pattern' => '/[a-zA-Z]/', 'index' => 0];
        $source = '1234567890';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The pattern /[a-zA-Z]/ could not be matched.');

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldThrowInvalidArgumentExceptionWhenIndexIsNotFoundInMatchedValues()
	{
        $schema = ['pattern' => '/(\d{4}-\d{2}-\d{2})\s(\d{2}:\d{2})/', 'index' => 5];
        $source = 'Today is 2020-09-28 00:22';

        $extractor = new RegexValueGetter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The index 5 does not exists in matched values in pattern /(\d{4}-\d{2}-\d{2})\s(\d{2}:\d{2})/.'
        );

        $extractor->getValue($schema, $source);
    }

    public function testGetValueShouldReturnTheIndexedMatchedValue()
	{
        $schema = ['pattern' => '/(\d{4}-\d{2}-\d{2})\s(\d{2}:\d{2})/', 'index' => 2];
        $source = 'Today is 2020-09-28 00:22';

        $extractor = new RegexValueGetter();

        $value = $extractor->getValue($schema, $source);

        $this->assertEquals('00:22', $value);
    }
}
