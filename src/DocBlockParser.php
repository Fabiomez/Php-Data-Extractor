<?php

namespace Fmezini\DataExtractor;

use InvalidArgumentException;
use ReflectionClass;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

class DocBlockParser
{
	/**
	 * @var \fmezini\CacherInterface
	 */
	private $cacher;

	/**
	 * @var \phpDocumentor\Reflection\DocBlockFactory
	 */
	private $docBlockFactory;

	public function __construct(CacherInterface $cacher, DocBlockFactoryInterface $docBlockFactory)
	{
		$this->cacher = $cacher;
		$this->docBlockFactory = $docBlockFactory;
	}

	/**
	 * Creates an instance.
	 *
	 * @param CacherInterface|null $cacher
	 * @param DocBlockFactoryInterface|null $docBlockFactory
	 *
	 * @return self
	 */
	public static function createInstance(
		CacherInterface $cacher = null,
		DocBlockFactoryInterface $docBlockFactory = null
	): self {
		$cacher = $cacher ?: new Cacher();
		$docBlockFactory = $docBlockFactory ?: DocBlockFactory::createInstance();

		return new self($cacher, $docBlockFactory);
	}

	/**
	 * Parse class properties tags
	 *
	 * @param string|object $model The model instance or classname to be parsed
	 *
	 * @return array The parsed class properties tags
	 */
	public function parse($model): array
	{
		$className = $this->getClassname($model);

		if ($this->cacher->has($className)) {
			return $this->cacher->get($className);
		}

		$parsedDocBlock = $this->parseClassProperties($className);

		$this->cacher->put($className, $parsedDocBlock);

		return $parsedDocBlock;
	}

	/**
	 * Get the model classname
	 *
	 * @param mixed $model
	 *
	 * @throws \InvalidArgumentException If the argument is not an object instance or a string namespace
	 *
	 * @return string The classname.
	 */
	public function getClassname($model): string
	{
		$classname =  is_string($model) ? $model : (
			is_object($model) ? get_class($model) : null
		);

		if (empty($classname)) {
			throw new InvalidArgumentException(
				'The $model argument must be an object instance or a class namespace'
			);
		}

		return $classname;
	}

	/**
	 * Parse the class properties tags
	 *
	 * @param string $className
	 *
	 * @return array
	 */
	public function parseClassProperties(string $className): array
	{
		$reflected = new ReflectionClass($className);

		$properties = $reflected->getProperties();

		$parsedProperties = [];

		foreach ($properties as $property) {
			$parsedProperties[$property->getName()] = $this->parsePropertyTags($property->getDocComment());
		}

		return $parsedProperties;
	}

	/**
	 * Parse the property tags key and description
	 *
	 * @param string $docBlock The property document block
	 *
	 * @return array
	 */
	public function parsePropertyTags(string $docBlock): array
	{
		$tags = $this->docBlockFactory->create($docBlock)->getTags();

		return $this->parseTags($tags);
	}

	public function parseTags(array $tags): array
	{
		$parsedTags = [];

		foreach ($tags as $tag) {
			$subTags = $tag->getDescription() ? $tag->getDescription()->getTags() : [];

			$parsedTags[(string) $tag->getName()] = !count($subTags) ?
				(string) $tag :
				$this->parseTags($subTags);
		}

		return $parsedTags;
	}
}
