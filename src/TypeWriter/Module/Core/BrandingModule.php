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
        return sprintf(__('Proudly running on TypeWriter by %s.', 'tw'), '<a href="https://glybe.nl/over-glybe/" target="_blank">Glybe</a>');
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

        return sprintf('TypeWriter %s | Raxos Foundation %s | WordPress %s', $versions['typewriter'], $versions['raxos_foundation'], $versions['wordpress']);
    }

}
