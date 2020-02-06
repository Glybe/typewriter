<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Facade;

use function in_array;

/**
 * Class Plugin
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Plugin
{

	private static ?array $activePlugins = null;

	/**
	 * Returns TRUE if the given plugin is active.
	 *
	 * @param string $name
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function active(string $name): bool
	{
		self::$activePlugins ??= get_option('active_plugins', []);

		return in_array($name, self::$activePlugins);
	}

	/**
	 * Returns the path of the given plugin, or NULL if the plugin doesn't exist.
	 *
	 * @param string $name
	 *
	 * @return string|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function dir(string $name): ?string
	{
		$path = WP_PLUGIN_DIR . '/' . $name;

		if (!is_dir($path))
			return null;

		return $path;
	}

	/**
	 * Returns TRUE if the given plugin exists.
	 *
	 * @param string $name
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function exists(string $name): bool
	{
		return self::dir($name) !== null;
	}

}
