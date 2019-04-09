<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Facade;

use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

/**
 * Class Hooks
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Hooks
{

	/**
	 * Adds an action hook.
	 *
	 * @param string   $action
	 * @param callable $fn
	 * @param int      $priority
	 *
	 * @throws HookException
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public static function action(string $action, callable $fn, int $priority = 10): void
	{
		if (!is_callable($fn))
			throw new HookException('$fn should be callable.', HookException::ERR_INVALID_CALLABLE);

		try
		{
			$reflect = is_array($fn) ? new ReflectionMethod($fn[0], $fn[1]) : new ReflectionFunction($fn);

			add_action($action, $fn, $priority, $reflect->getNumberOfParameters());
		}
		catch (ReflectionException $err)
		{
			throw new HookException('Reflection failed on $fn', HookException::ERR_REFLECTION_FAILED, $err);
		}
	}

	/**
	 * Adds a filter hook.
	 *
	 * @param string   $filter
	 * @param callable $fn
	 * @param int      $priority
	 *
	 * @throws HookException
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public static function filter(string $filter, callable $fn, int $priority = 10): void
	{
		if (!is_callable($fn))
			throw new HookException('$fn should be callable.', HookException::ERR_INVALID_CALLABLE);

		try
		{
			$reflect = is_array($fn) ? new ReflectionMethod($fn[0], $fn[1]) : new ReflectionFunction($fn);

			add_filter($filter, $fn, $priority, $reflect->getNumberOfParameters());
		}
		catch (ReflectionException $err)
		{
			throw new HookException('Reflection failed on $fn', HookException::ERR_REFLECTION_FAILED, $err);
		}
	}

}
