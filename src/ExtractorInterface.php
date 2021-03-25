<?php

namespace Fabiomez\DataExtractor;

interface ExtractorInterface
{
	/**
	 * Extreact the data from source string into the model
	 *
	 * Uses the models attributes docblock tags as reference to parse the string
	 * The attributes must have @start and @length tags with int description
	 *
	 * @param object|string $model The model
	 * @param string $source The source text to be parsed into the model
	 * @param callable $callback Invoked after extraction passing as argument the $model and the properties tags schema.
	 *
	 * @throws InvalidArgumentException On invalid tags or tags values
	 */
	public function extract($model, $source, $callback = null);
}
