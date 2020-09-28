<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Twig\TokenParser;

use Twig\Compiler;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class AbstractRenderTokenParser
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\TokenParser
 * @since 1.0.0
 */
abstract class AbstractRenderTokenParser extends AbstractTokenParser
{

    protected string $tagName;

    /**
     * AbstractRenderTokenParser constructor.
     *
     * @param string $tagName
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function parse(Token $token): Node
    {
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return new class($this, $token->getLine(), $this->getTag()) extends Node {

            private AbstractRenderTokenParser $tokenParser;

            /**
             * anonymous@Node constructor.
             *
             * @param AbstractRenderTokenParser $tokenParser
             * @param int $lineNumber
             * @param string $tag
             *
             * @author Bas Milius <bas@mili.us>
             * @since 1.0.0
             */
            public function __construct(AbstractRenderTokenParser $tokenParser, int $lineNumber, string $tag)
            {
                parent::__construct([], [], $lineNumber, $tag);

                $this->tokenParser = $tokenParser;
            }

            /**
             * {@inheritDoc}
             * @author Bas Milius <bas@mili.us>
             * @since 1.0.0
             */
            public function compile(Compiler $compiler): void
            {
                $compiler->raw($this->tokenParser->generateCall());
            }

        };
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getTag(): string
    {
        return $this->tagName;
    }

    /**
     * Renders the content.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public abstract function generateCall(): string;

}
