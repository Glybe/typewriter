<?php
declare(strict_types=1);

namespace TypeWriter\Twig\TokenParser;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use TypeWriter\Twig\Node\ControllerNode;
use function class_exists;

/**
 * Class ControllerTokenParser
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\TokenParser
 * @since 1.0.0
 */
final class ControllerTokenParser extends AbstractTokenParser
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        if (!$this->parser->isMainScope()) {
            throw new SyntaxError('You can only assign a controller in the main scope.', $token->getLine(), $stream->getSourceContext());
        }

        $expression = $this->parser->getExpressionParser()->parseExpression();

        if (!$expression->hasAttribute('value') || !class_exists($expression->getAttribute('value'))) {
            throw new SyntaxError('You must define a valid controller.', $token->getLine(), $stream->getSourceContext());
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new ControllerNode($expression->getAttribute('value'), $token->getLine());
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getTag(): string
    {
        return 'controller';
    }

}
