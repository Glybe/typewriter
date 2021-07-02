<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;

/**
 * Class DisableCoreFeaturesModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DisableCoreFeaturesModule extends Module
{

    /**
     * DisableCoreFeaturesModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Disables core features on the website.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('do_redirect_guess_404_permalink', fn() => false);

        Hooks::removeAction('init', 'wp_widgets_init', 1);
    }

}
