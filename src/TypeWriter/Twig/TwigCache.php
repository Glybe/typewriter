<?php
declare(strict_types=1);

namespace TypeWriter\Twig;

use Twig\Cache\FilesystemCache;
use const TypeWriter\ROOT;

/**
 * Class TwigCache
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig
 * @since 1.0.0
 */
final class TwigCache extends FilesystemCache
{

    /**
     * TwigCache constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(ROOT . '/cache/twig');
    }

}
