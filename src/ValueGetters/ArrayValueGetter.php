<?php

namespace Fabiomez\DataExtractor\ValueGetters;

use Webmozart\Assert\Assert;

class ArrayValueGetter implements ValueGetterInterface
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
		Assert::keyExists($schema, 'index');
		Assert::keyExists($source, $schema['index']);

		return $source[$schema['index']];
	}
}
