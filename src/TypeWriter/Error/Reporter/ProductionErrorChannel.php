<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use Raxos\Http\HttpCode;
use Throwable;
use function file_get_contents;
use function http_response_code;
use const TypeWriter\ROOT;

/**
 * Class ProductionErrorChannel
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Reporter
 * @since 1.0.0
 */
final class ProductionErrorChannel extends ErrorChannel
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onException(Throwable $err, ?array $context): void
    {
        http_response_code(HttpCode::INTERNAL_SERVER_ERROR);

        echo file_get_contents(ROOT . '/resource/view/html/error.html');
    }

}
