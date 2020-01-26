<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino\Controller;

use TypeWriter\Facade\Post;
use function get_the_content;
use function get_the_ID;
use function get_the_title;
use function the_post;

/**
 * Class PostController
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino\Controller
 * @since 1.0.0
 */
final class PostController extends Controller
{

	/**
	 * PostController constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		the_post();
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getContext(): array
	{
		return [
			'post' => [
				'id' => Post::id(),
				'title' => Post::title(),
				'content' => get_the_content()
			]
		];
	}

}
