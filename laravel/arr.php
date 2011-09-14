<?php namespace Laravel;

class Arr {

	/**
	 * Get an item from an array.
	 *
	 * If the specified key is null, the entire array will be returned. The array may
	 * also be accessed using JavaScript "dot" style notation. Retrieving items nested
	 * in multiple arrays is supported.
	 *
	 * <code>
	 *		// Get the "name" item from the array
	 *		$name = Arr::get(array('name' => 'Fred'), 'name');
	 *
	 *		// Get the "age" item from the array, or return 25 if it doesn't exist
	 *		$name = Arr::get(array('name' => 'Fred'), 'age', 25);
	 * </code>
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null)
	{
		if (is_null($key)) return $array;

		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_array($array) or ! array_key_exists($segment, $array))
			{
				return ($default instanceof \Closure) ? call_user_func($default) : $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Set an array item to a given value.
	 *
	 * This method is primarly helpful for setting the value in an array with
	 * a variable depth, such as configuration arrays. Like the Arr::get
	 * method, JavaScript "dot" syntax is supported.
	 *
	 * <code>
	 *		// Set an array's "name" item to "Fred"
	 *		Arr::set($array, 'name', 'Fred');
	 * </code>
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function set(&$array, $key, $value)
	{
		if (is_null($key)) return $array = $value;

		$keys = explode('.', $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if ( ! isset($array[$key]) or ! is_array($array[$key]))
			{
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}

	/**
	 * Return the first element in an array which passes a given truth test.
	 *
	 * <code>
	 *		// Get the first element in an array that is less than 2
	 *		$value = Arr::first(array(4, 3, 2, 1), function($key, $value) {return $value < 2;});
	 * </code>
	 *
	 * @param  array    $array
	 * @param  Closure  $callback
	 * @return mixed
	 */
	public static function first($array, $callback)
	{
		foreach ($array as $key => $value)
		{
			if (call_user_func($callback, $key, $value)) return $value;
		}
	}

}