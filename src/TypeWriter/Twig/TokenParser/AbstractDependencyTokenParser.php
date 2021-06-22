<?php
declare(strict_types=1);

namespace TypeWriter\Twig\TokenParser;

use Twig\Error\SyntaxError;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class AbstractDependencyTokenParser
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\TokenParser
 * @since 1.0.0
 */
abstract class AbstractDependencyTokenParser extends AbstractTokenParser
{

    /**
     * AbstractDependencyTokenParser constructor.
     *
     * @param string $tagName
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(protected string $tagName)
    {
    }

    /**
     * Creates the source node that loads the dependency.
     *
     * @param string $name
     * @param AbstractExpression $expression
     *
     * @return Node
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected abstract function createNode(string $name, AbstractExpression $expression): Node;

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        if (!$this->parser->isMainScope()) {
            throw new SyntaxError('Dependencies can only load in the main scope.', $token->getLine(), $stream->getSourceContext());
        }

        $expressionParser = $this->parser->getExpressionParser();

        $nameExpression = $expressionParser->parseExpression();
        $valueExpression = $expressionParser->parseExpression();

        if (!$nameExpression->hasAttribute('value')) {
            throw new SyntaxError("Dependencies should have a name.");
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return $this->createNode($nameExpression->getAttribute('value'), $valueExpression);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getTag(): string
    {
        return $this->tagName;
    }

}
