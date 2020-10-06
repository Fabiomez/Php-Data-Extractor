<?php

namespace Fmezini\DataExtractor\ValueGetters;

use Webmozart\Assert\Assert;
use InvalidArgumentException;

class RegexValueGetter implements ValueGetterInterface
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
		Assert::keyExists($schema, 'pattern');
		Assert::string($schema['pattern']);
		Assert::keyExists($schema, 'index');
		Assert::integer($schema['index']);

		if (!preg_match($schema['pattern'], $source, $matches)) {
			throw new InvalidArgumentException(sprintf(
				'The pattern %s could not be matched.',
				$schema['pattern']
			));
		}

		if (!isset($matches[$schema['index']])) {
			throw new InvalidArgumentException(sprintf(
				'The index %d does not exists in matched values in pattern %s.',
				$schema['index'],
				$schema['pattern']
			));
		}

		return $matches[$schema['index']];
	}
}