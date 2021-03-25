<?php

namespace Fabiomez\DataExtractor;

use InvalidArgumentException;
use Fabiomez\DataExtractor\ValueGetters\ValueGetterInterface;
use UnexpectedValueException;

class Extractor implements ExtractorInterface
{
	/**
	 * @var DocBlockParser
	 */
	protected $docBlockParser;

	/**
	 * @var ValueGetterInterface
	 */
	protected $valueGetter;

	/**
	 * Constructs a new instance.
	 *
	 * @param DocBlockParser $docBlockParser The document block parser
	 */
	public function __construct(DocBlockParser $docBlockParser, ValueGetterInterface $valueGetter)
	{
		$this->docBlockParser = $docBlockParser;
		$this->valueGetter = $valueGetter;
	}

	/**
	 * Extreact the data from source string into the model
	 *
	 * Uses the models attributes docblock tags as reference to parse the string
	 * The attributes must have @start and @length tags with int description
	 *
	 * @param object|string $model The model
	 * @param string $source The source text to be parsed into the model
	 * @param callable $callback Invoked after extraction. Passing as argument the $model and properties tags schema.
	 * 
	 * @return mixed The new version of the model filled with stracted data from source
	 *
	 * @throws InvalidArgumentException On invalid tags or tags values
	 */
	public function extract($model, $source, $callback = null)
	{
		$propertiesSchema = $this->docBlockParser->parse($model);

		$model = $this->getModelInstance($model);

		try {
			foreach ($propertiesSchema as $property => $schema) {
				if (!isset($schema['extractable']) || !is_array($schema['extractable'])) {
					continue;
				}

				$value = $this->valueGetter->getValue($schema['extractable'], $source);

				if (!empty($value)) {
					$model->{$property} = $value;
				}
			}

			if (is_callable($callback)) {
				call_user_func($callback, $model, $propertiesSchema);
			}

			return $model;
		} catch (InvalidArgumentException $exception) {
			throw new InvalidArgumentException(sprintf(
				'%s For %s::$%s.',
				$exception->getMessage(),
				get_class($model),
				$property
			));
		}
	}

	public function getModelInstance($model)
	{
		if (is_string($model)) {
			return new $model();
		}

		if (is_object($model)) {
			return clone $model;
		}

		throw new UnexpectedValueException('The suplied model must be a class namespace or an object instance');
	}
}
