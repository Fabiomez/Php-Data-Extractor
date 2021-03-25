<?php

namespace Fabiomez\DataExtractor;

interface CacherInterface
{
	/**
	 * Get a stored value from the cache
	 *
	 * @param string $key The key previous stored in cache
	 * @return mixed
	 */
	public function get(string $key);

	/**
	 * Put a value on cache specified by the given key
	 *
	 * @param string $key The key to store the value
	 * @param mixed $value The value to be stored
	 * @return void
	 */
	public function put(string $key, $value): void;

	/**
	 * Deletes the item from the cache by the given key
	 *
	 * @param string $key
	 * @return void
	 */
	public function del(string $key): void;

	/**
	 * Determines whatever or not a values has been stored by the given key
	 *
	 * @param string $key The key of the item to be verified
	 *
	 * @return boolean
	 */
	public function has(string $key): bool;
}
