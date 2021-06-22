<?php
declare(strict_types=1);

namespace TypeWriter\Twig\Node;

use Twig\Node\Expression\AbstractExpression;

/**
 * Class ScriptNode
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\Node
 * @since 1.0.0
 */
final class ScriptNode extends DependencyNode
{

    /**
     * ScriptNode constructor.
     *
     * @param string $name
     * @param AbstractExpression $pathExpression
     * @param int $lineNumber
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $name, AbstractExpression $pathExpression, int $lineNumber = 0)
    {
        parent::__construct('enqueueScript', $name, $pathExpression, $lineNumber);
    }

}
