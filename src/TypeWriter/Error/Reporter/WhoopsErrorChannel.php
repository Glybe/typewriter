<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use Throwable;
use Whoops\Run;

/**
 * Class WhoopsErrorChannel
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Reporter
 * @since 1.0.0
 */
final class WhoopsErrorChannel extends ErrorChannel
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onException(Throwable $err, ?array $context): void
    {
        $handler = new WhoopsPrettyPageHandler();

        if ($context !== null) {
            $handler->addDataTable('Context', $context);
        }

        $whoops = new Run();
        $whoops->appendHandler($handler);

        echo $whoops->handleException($err);
    }

}
