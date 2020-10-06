<?php

namespace Fmezini\DataExtractor;

use Fmezini\DataExtractor\ValueGetters\ArrayValueGetter;
use Fmezini\DataExtractor\ValueGetters\RegexValueGetter;
use Fmezini\DataExtractor\ValueGetters\SubstringValueGetter;

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
