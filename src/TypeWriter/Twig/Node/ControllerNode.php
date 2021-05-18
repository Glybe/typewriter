<?php
declare(strict_types=1);

namespace TypeWriter\Twig\Node;

use Raxos\Foundation\Util\Singleton;
use Twig\Compiler;
use Twig\Node\Node;
use const PHP_EOL;

/**
 * Class ControllerNode
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\Node
 * @since 1.0.0
 */
final class ControllerNode extends Node
{

    /**
     * ControllerNode constructor.
     *
     * @param string $controller
     * @param int $lineNumber
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $controller, int $lineNumber = 0)
    {
        parent::__construct([], [], $lineNumber, 'controller');

        $this->setAttribute('controller', $controller);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        $compiler->write('$controller = ' . Singleton::class . '::get(' . $this->getAttribute('controller') . ');' . PHP_EOL);
        $compiler->write('$context = array_merge($context, $controller->getContext());');
    }

}
