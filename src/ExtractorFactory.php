<?php

namespace Fabiomez\DataExtractor;

use Fabiomez\DataExtractor\ValueGetters\ArrayValueGetter;
use Fabiomez\DataExtractor\ValueGetters\RegexValueGetter;
use Fabiomez\DataExtractor\ValueGetters\SubstringValueGetter;

class ExtractorFactory
{
	public function createSubstringExtractor(): ExtractorInterface
	{
		return new Extractor(
			DocBlockParser::createInstance(),
			new SubstringValueGetter()
		);
	}

	public function createArrayExtractor(): ExtractorInterface
	{
		return new Extractor(
			DocBlockParser::createInstance(),
			new ArrayValueGetter()
		);
	}

	public function createRegexExtractor(): ExtractorInterface
	{
		return new Extractor(
			DocBlockParser::createInstance(),
			new RegexValueGetter()
		);
	}
}
