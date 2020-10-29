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

namespace TypeWriter\Facade;

use function TypeWriter\tw;

/**
 * Class AdminUI
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class AdminUI
{

    /**
     * Echoes a notice.
     *
     * @param string $type
     * @param string $message
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function notice(string $type, string $message): void
    {
        self::ensureAdmin();

        echo <<<NOTICE
        <div class="notice notice-{$type} is-dismissible">
            <p>{$message}</p>
        </div>
        NOTICE;

    }

    /**
     * Ensures that we're in wp-admin.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private static function ensureAdmin(): void
    {
        if (tw()->isAdmin()) {
            return;
        }

        DeveloperNotice::base('error', 'Not how that works!', function (): void {
            echo <<<HTML
                <p>
                    AdminUI functions can only be used in wp-admin.
                </p>
            HTML;
        });
    }

}
