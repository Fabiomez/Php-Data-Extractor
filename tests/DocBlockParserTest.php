<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Fabiomez\DataExtractor\DocBlockParser;

class DocBlockParserTest extends TestCase
{
	public function testGetClassnameShouldReturningTheInformedValueWhenIsString(): void
	{
		$parser = DocBlockParser::createInstance();

		$informedClassName = 'A class name';

		$returnedClassName = $parser->getClassname($informedClassName);

		$this->assertEquals($informedClassName, $returnedClassName);
	}

	public function testGetClassnameShouldReturningTheClassNamespace(): void
	{
		$parser = DocBlockParser::createInstance();

		$returnedClassName = $parser->getClassname($parser);

		$this->assertEquals(DocBlockParser::class, $returnedClassName);
	}

	public function testGetClassnameShouldThrowInvalidArgumentExceptionWhenInvalidValueIsInformed(): void
	{
		$parser = DocBlockParser::createInstance();

		$this->expectException(\InvalidArgumentException::class);

		$returnedClassName = $parser->getClassname(false);
	}

	public function testParsePropertyTagsReturnFormat(): void
	{
		$docBlock = '/**
		 * Method title
		 *
		 * Method Description
		 *
		 * @firstTag First value
		 * @secondTag Second value
		 * @parentTag
		 *    {@childTag1 child value 1}
		 *    {@childTag2 child value 2}
		 */';

		$parser = DocBlockParser::createInstance();

		$parsedTags = $parser->parsePropertyTags($docBlock);

		$this->assertEquals(
			[
				'firstTag' => 'First value',
				'secondTag' => 'Second value',
				'parentTag' => [
					'childTag1' => 'child value 1',
					'childTag2' => 'child value 2',
				]
			],
			$parsedTags
		);
	}

	public function testParseClassPropertiesReturnFormat(): void
	{
		$class = new class () {
			/**
			 * @firstTag First value
		 	 * @secondTag Second value
			 */
			public $prop1;
			/**
			 * @firstTag First value
		 	 * @secondTag Second value
			 */
			public $prop2;
		};

		$parser = DocBlockParser::createInstance();

		$parsedProperties = $parser->parse($class);

		$this->assertEquals(
			[
				'prop1' => [
					'firstTag' => 'First value',
					'secondTag' => 'Second value'
				],
				'prop2' => [
					'firstTag' => 'First value',
					'secondTag' => 'Second value'
				],
			],
			$parsedProperties
		);
	}
}
