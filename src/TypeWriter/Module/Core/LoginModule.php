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

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Site;
use TypeWriter\Module\Module;

// note: To display a custom message above the form, add login_message filter hook.

/**
 * Class LoginModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class LoginModule extends Module
{

    /**
     * LoginModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Changes the login page.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('login_enqueue_scripts', [$this, 'onLoginEnqueueScripts']);
        Hooks::action('login_head', [$this, 'onLoginHead']);

        Hooks::filter('login_headertext', [$this, 'onLoginHeaderText']);
        Hooks::filter('login_headerurl', [$this, 'onLoginHeaderUrl']);
        Hooks::filter('login_title', [$this, 'onLoginTitle']);
    }

    /**
     * Invoked on login_enqueue_scripts action hook.
     * Adds our custom styling to the login page.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onLoginEnqueueScripts(): void
    {
        Dependencies::registerStyle('tw', home_url('/tw/dist/admin.css'));
        Dependencies::enqueueStyle('tw');
    }

    /**
     * Invoked on login_head action hook.
     * Adds our custom branding logo to the login form.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onLoginHead(): void
    {
        echo <<<STYLE
            <style>
            body { background: #eef2f7; }
            .login h1 a { background-image: url(https://cdn.glybe.nl/public/brand/SVG/logo-horizontal.svg); background-size: contain; height: 39px; width: 125px; }
            .login label { margin-bottom: 6px; font-weight: 700; }
            #loginform { padding: 24px; border-color: #dbe2ea; border-radius: 6px; box-shadow: none; }
            </style>
        STYLE;
    }

    /**
     * Invoked on login_headertext filter hook.
     * Returns a custom logo url.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onLoginHeaderText(): string
    {
        return 'Powered by TypeWriter';
    }

    /**
     * Invoked on login_headerurl filter hook.
     * Returns a custom logo url.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onLoginHeaderUrl(): string
    {
        return 'https://glybe.nl/';
    }

    /**
     * Invoked on login_title filter hook.
     * Overrides the title of the login page.
     *
     * @param string $_
     * @param string $title
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onLoginTitle(string $_, string $title): string
    {
        $name = Site::info('name');

        return "$title &mdash; $name / TypeWriter";
    }

}
