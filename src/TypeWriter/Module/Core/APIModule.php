<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;

/**
 * Class APIModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class APIModule extends Module
{

    /**
     * APIModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Changes various things for wp-json.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('rest_url_prefix', [$this, 'onWordPressRestUrlPrefix']);
    }

    /**
     * Invoked on rest_url_prefix hook.
     * Changes the wp-json path to just api.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onWordPressRestUrlPrefix(): string
    {
        return 'api';
    }

}
