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

/**
 * Class DeveloperNotice
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class DeveloperNotice
{

    /**
     * Create a base error notice page.
     *
     * @param string $type
     * @param string $title
     * @param callable $render
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function base(string $type, string $title, callable $render): void
    {
        ob_get_clean();

        echo <<<TEMPLATE
        <!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8"/>
            <meta name="robots" content="noindex"/>
            <meta name="viewport" content="width=device-with, initial-scale=1.0"/>
            <title>{$title}</title>
            <link rel="stylesheet" href="/wp/wp-admin/css/wp-admin.min.css"/>
            <style>
                body { display: flex; min-height: 100vh; background: #eef2f7; color: #191a1c; font-size: 15px; }
                h3 { color: var(--color, inherit); font-size: 18px; font-weight: 700; }
                p { font-size: 15px; }
                main { position: relative; margin: auto; max-width: 510px; width: calc(100vw - 48px); }
                .notice { border: 1px solid #dbe2ea; border-left: 0; box-shadow: -4px 0 var(--color); }
                .notice-error { --color: #f50008; }
                .notice-info { --color: #007bc8; }
                .notice-success { --color: #00885c; }
                .notice-warning { --color: #fba700; }
                .notice p { margin-left: 0; margin-right: 0; padding: 0; }
                ul { list-style-type: disc; margin-left: 18px; }
            </style>
        </head>
        <body>
        <main class="metabox-holder">
            <div class="notice notice-{$type}" style="margin: 0; padding: 12px 24px;">
                <h3>{$title}</h3>
        TEMPLATE;

        $render();

        echo <<<TEMPLATE
            </div>
        </main>
        </body>
        </html>
        TEMPLATE;

        die;
    }

}
