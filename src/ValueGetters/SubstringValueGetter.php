<?php

namespace Fmezini\DataExtractor\ValueGetters;

use Webmozart\Assert\Assert;

class SubstringValueGetter implements ValueGetterInterface
{
	/**
	 * Gets the value from source using $schema specification
	 *
	 * @param array $schema The schema that contains the specifications
	 * @param mixed $source The source that contains the data to be got
	 *
	 * @return mixed The value got from the source
	 */
	public function getValue(array $schema, $source)
	{
		Assert::string($source);
		Assert::keyExists($schema, 'start');
		Assert::integer($schema['start']);
		Assert::keyExists($schema, 'length');
		Assert::integer($schema['length']);

		return substr($source, $schema['start'], $schema['length']);
	}
}
