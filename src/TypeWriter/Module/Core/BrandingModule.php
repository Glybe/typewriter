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

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function __;
use function sprintf;
use function TypeWriter\tw;
use function vsprintf;

/**
 * Class BrandingModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class BrandingModule extends Module
{

    /**
     * BrandingModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Applies the TypeWriter branding on various places.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('admin_footer_text', [$this, 'onAdminFooterText']);
        Hooks::filter('update_footer', [$this, 'onUpdateFooter'], 11);
    }

    /**
     * Invoked on admin_footer_text filter hook.
     * Returns custom footer text with the TypeWriter version etc.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminFooterText(): string
    {
        return __('Proudly running on TypeWriter', 'tw');
    }

    /**
     * Invoked on update_footer filter hook.
     * Returns the current TypeWriter and WordPress versions.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onUpdateFooter(): string
    {
        $versions = tw()->getVersions();

        return vsprintf('%s | %s | %s', [
            sprintf('<a style="color: #6b7280;" href="https://github.com/glybe/typewriter" rel="noopener" target="_blank">TypeWriter %s</a>', $versions['typewriter']),
            sprintf('<a style="color: #6b7280;" href="https://github.com/basmilius/raxos" rel="noopener" target="_blank">Raxos %s</a>', $versions['raxos_foundation']),
            sprintf('<a style="color: #6b7280;" href="https://wordpress.org/" rel="noopener" target="_blank">WordPress %s</a>', $versions['wordpress'])
        ]);
    }

}
