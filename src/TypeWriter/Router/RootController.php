<?php
declare(strict_types=1);

namespace TypeWriter\Router;

use Composer\InstalledVersions;
use Raxos\Router\Attribute\Get;
use Raxos\Router\Response\Response;

/**
 * Class RootController
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Router
 * @since 1.0.0
 */
final class RootController extends Controller
{

    /**
     * Invoked when GET /framework-info is requested.
     *
     * @return Response
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Get('/framework-info')]
    protected final function getFrameworkInfo(): Response
    {
        return $this->json([
            'name' => 'TypeWriter',
            'version' => InstalledVersions::getPrettyVersion('basmilius/typewriter')
        ]);
    }

}
