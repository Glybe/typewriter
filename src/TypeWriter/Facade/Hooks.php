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

use function add_action;
use function add_filter;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use function apply_filters;
use function do_action;
use function is_array;
use function is_callable;
use function remove_action;
use function remove_filter;

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
	 * Applies filters to $value.
	 *
	 * @param string $filter
	 * @param mixed  $value
	 * @param mixed  $more
	 *
	 * @return mixed
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function applyFilters(string $filter, $value, ...$more)
	{
		return apply_filters($filter, $value, ...$more);
	}

	/**
	 * Applies filters to $value.
	 *
	 * @param string $action
	 * @param mixed  $more
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function doAction(string $action, ...$more): void
	{
		do_action($action, ...$more);
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

	/**
	 * Removes an action callback.
	 *
	 * @param string   $action
	 * @param callable $fn
	 * @param int      $priority
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function removeAction(string $action, callable $fn, int $priority = 10): void
	{
		remove_action($action, $fn, $priority);
	}

	/**
	 * Removes a filter callback.
	 *
	 * @param string   $filter
	 * @param callable $fn
	 * @param int      $priority
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function removeFilter(string $filter, callable $fn, int $priority = 10): void
	{
		remove_filter($filter, $fn, $priority);
	}

}
