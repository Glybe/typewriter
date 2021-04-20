<?php
declare(strict_types=1);

namespace TypeWriter\Router;

use Composer\InstalledVersions;
use Raxos\Router\Attribute\Get;
use Raxos\Router\Response\Response;

/**
 * Class RootController
 *
 * @author Bas Milius <bas@glybe.nl>
 * @package TypeWriter\Router
 * @since 2.0.0
 */
final class RootController extends Controller
{

    #[Get('/framework-info')]
    protected final function getFrameworkInfo(): Response
    {
        return $this->json([
            'name' => 'TypeWriter',
            'version' => InstalledVersions::getPrettyVersion('basmilius/typewriter')
        ]);
    }

}
