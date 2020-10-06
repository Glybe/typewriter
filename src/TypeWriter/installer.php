<?php
/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    die;
}

/*
 * Runs before the WordPress installer. We're overriding
 * some core functions here so that the installer works
 * perfectly with our framework.
 */

/**
 * Returns FALSE so that auth checks are skipped during
 * the installation process.
 *
 * @return bool
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function is_user_logged_in(): bool
{
    return false;
}

/**
 * Returns NULL so that auth checks are skipped during the
 * installation process.
 *
 * @return WP_User|null
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function wp_get_current_user(): ?WP_User
{
    return null;
}
