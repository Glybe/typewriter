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

namespace TypeWriter\Router;

use Raxos\Foundation\Util\Debug;
use Raxos\Router\Effect\NotFoundEffect;
use Raxos\Router\Effect\RedirectEffect;
use Raxos\Router\Effect\ResponseEffect;
use Raxos\Router\Effect\VoidEffect;
use Raxos\Router\Error\RouterException;
use Raxos\Router\Error\RuntimeException;
use Raxos\Router\Response\HtmlResponse;
use Raxos\Router\Router as RaxosRouter;
use function TypeWriter\request;
use function TypeWriter\tw;

/**
 * Class Router
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Router
 * @since 1.0.0
 */
final class Router extends RaxosRouter
{

    /**
     * Resolves and responses.
     *
     * @param string|null $method
     * @param string|null $path
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function resolveAndRespond(?string $method = null, ?string $path = null): bool
    {
        $request = request();

        $method ??= $request->method();
        $path ??= $request->pathName();

        $method = strtolower($method);

        $this->global('request', $request);
        $this->global('tw', tw());

        try {
            $this->controller(RootController::class);

            // Sorry Google, we don't want this :)
            $this->getResponseRegistry()->header('Permissions-Policy', 'interest-cohort=()');

            $result = $this->resolve($method, $path);

            switch (true) {
                case $result instanceof NotFoundEffect:
                    return false;

                case $result instanceof RedirectEffect:
                    $this->onRedirectEffect($result);

                    return true;

                case $result instanceof ResponseEffect:
                    $this->onResponseEffect($result);

                    return true;

                case $result instanceof VoidEffect:
                    $this->onVoidEffect($result);

                    return true;

                default:
                    throw new RuntimeException(sprintf('Did not handle router effect of type %s.', get_class($result)));
            }
        } catch (RouterException $err) {
            Debug::printDie($err);
        }
    }

    /**
     * Invoked on a redirect effect.
     *
     * @param RedirectEffect $effect
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function onRedirectEffect(RedirectEffect $effect): void
    {
        $response = new HtmlResponse($effect->getRouter(), '...');

        $response
            ->getRouter()
            ->getResponseRegistry()
            ->header('Location', $effect->getDestination())
            ->responseCode($effect->getResponseCode());

        $response->respond();
    }

    /**
     * Invoked on a response effect.
     *
     * @param ResponseEffect $effect
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function onResponseEffect(ResponseEffect $effect): void
    {
        $effect->getResponse()->respond();
    }

    /**
     * Invoked on a void effect.
     *
     * @param VoidEffect $effect
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function onVoidEffect(VoidEffect $effect): void
    {
    }

}
