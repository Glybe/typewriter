<?php
declare(strict_types=1);

namespace TypeWriter\Twig\Node;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;
use TypeWriter\Facade\Dependencies;

/**
 * Class DependencyNode
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\Node
 * @since 1.0.0
 */
abstract class DependencyNode extends Node
{

    /**
     * DependencyNode constructor.
     *
     * @param string $methodName
     * @param string $name
     * @param AbstractExpression $pathExpression
     * @param int $lineNumber
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(private string $methodName, private string $name, private AbstractExpression $pathExpression, int $lineNumber = 0)
    {
        parent::__construct([], [], $lineNumber, 'dependency');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function compile(Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler->write(Dependencies::class . "::{$this->methodName}('{$this->name}', ");
        $this->pathExpression->compile($compiler);
        $compiler->write(');');
    }

}
