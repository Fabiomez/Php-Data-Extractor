<?php

namespace Fabiomez\DataExtractor;

class Cacher implements CacherInterface
{
	private static $items = [];
	/**
	 * Get a stored value from the cache
	 *
	 * @param string $key The key previous stored in cache
	 * @return mixed
	 */
	public function get(string $key)
	{
		return self::$items[$key] ?? null;
	}

	/**
	 * Put a value on cache specified by the given key
	 *
	 * @param string $key The key to store the value
	 * @param mixed $value The value to be stored
	 * @return void
	 */
	public function put(string $key, $value): void
	{
		self::$items[$key] = $value;
	}

	/**
	 * Deletes the item from the cache by the given key
	 *
	 * @param string $key
	 * @return void
	 */
	public function del(string $key): void
	{
		if ($this->has($key)) {
			unset(self::$items[$key]);
		}
	}

	/**
	 * Determines whatever or not a values has been stored by the given key
	 *
	 * @param string $key The key of the item to be verified
	 *
	 * @return boolean
	 */
	public function has(string $key): bool
	{
		return isset(self::$items[$key]);
	}
}