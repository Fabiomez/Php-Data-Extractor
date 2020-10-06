<?php

namespace Fmezini\DataExtractor\ValueGetters;

interface ValueGetterInterface
{
	/**
	 * Gets the value from source using $schema specification
	 *
	 * @param array $schema The schema that contains the specifications
	 * @param mixed $source The source that contains the data to be got
	 *
	 * @return mixed The value got from the source
	 */
	public function getValue(array $schema, $source);
}
