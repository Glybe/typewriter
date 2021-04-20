<?php
declare(strict_types=1);

namespace TypeWriter\Router;

use JetBrains\PhpStorm\ExpectedValues;
use Raxos\Http\HttpCode;
use Raxos\Router\Controller\Controller as RaxosController;
use Raxos\Router\Response\HtmlResponse;
use function TypeWriter\twig;

/**
 * Class Controller
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Router
 * @since 1.0.0
 */
abstract class Controller extends RaxosController
{

    /**
     * Renders the given template and returns a html response.
     *
     * @param string $template
     * @param array $context
     * @param int $responseCode
     *
     * @return HtmlResponse
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected final function twig(string $template, array $context = [], #[ExpectedValues(valuesFromClass: HttpCode::class)] int $responseCode = HttpCode::OK): HtmlResponse
    {
        $result = twig()->render($template, $context);

        return $this->html($result, $responseCode);
    }

}
