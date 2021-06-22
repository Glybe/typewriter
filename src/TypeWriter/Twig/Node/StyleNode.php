<?php
declare(strict_types=1);

namespace TypeWriter\Twig\Node;

use Twig\Node\Expression\AbstractExpression;

/**
 * Class StyleNode
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\Node
 * @since 1.0.0
 */
final class StyleNode extends DependencyNode
{

    /**
     * StyleNode constructor.
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
        parent::__construct('enqueueStyle', $name, $pathExpression, $lineNumber);
    }

}
