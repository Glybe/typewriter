<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use TypeWriter\Error\Runtime\PhpException;
use Whoops\Exception\FrameCollection;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class WhoopsPrettyPageHandler
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Reporter
 * @since 1.0.0
 */
final class WhoopsPrettyPageHandler extends PrettyPageHandler
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function getExceptionFrames(): FrameCollection
    {
        $frames = parent::getExceptionFrames();

        if ($this->getException() instanceof PhpException) {
            $index = -1;

            $frames = $frames->filter(function () use (&$index): bool {
                return ++$index >= 2;
            });
        }

        return $frames;
    }

}
