<?php
declare(strict_types=1);

namespace TypeWriter;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;

/**
 * Class Environment
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter
 * @since 1.0.0
 */
final class Environment
{

    private Dotenv $dot;
    private RepositoryInterface $repository;
    private array $variables = [];

    /**
     * Environment constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->repository = RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(EnvConstAdapter::class)
            ->addAdapter(PutenvAdapter::class)
            ->immutable()
            ->make();

        $this->dot = Dotenv::create($this->repository, ROOT, ['defaults.env', '.env'], false);
    }

    /**
     * Initialize environment variables.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function initialize(): void
    {
        $this->variables = $this->dot->load();
    }

    /**
     * Gets an environment variable value.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function get(string $key, $default = null): mixed
    {
        return $this->variables[$key] ?? $default;
    }

}
