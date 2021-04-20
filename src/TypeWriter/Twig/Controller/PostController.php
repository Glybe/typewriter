<?php
declare(strict_types=1);

namespace TypeWriter\Twig\Controller;

use JetBrains\PhpStorm\ArrayShape;
use TypeWriter\Facade\Post;

/**
 * Class PostController
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig\Controller
 * @since 1.0.0
 */
class PostController extends Controller
{

    /**
     * PostController constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        Post::next();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[ArrayShape([
        'post' => Post::class
    ])]
    public function getContext(): array
    {
        return [
            'post' => new Post()
        ];
    }

}
