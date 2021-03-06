<?php
declare(strict_types=1);

namespace TypeWriter\Twig\TokenParser;

use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;
use TypeWriter\Twig\Node\ScriptNode;

/**
 * Class ScriptTokenParser
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\TokenParser
 * @since 1.0.0
 */
final class ScriptTokenParser extends AbstractDependencyTokenParser
{

    /**
     * ScriptTokenParser constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('script');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected final function createNode(string $name, AbstractExpression $expression): Node
    {
        return new ScriptNode($name, $expression);
    }

}
