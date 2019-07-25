<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino\Controller;

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
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getContext(): array
	{
		the_post();

		return [
			'post' => [
				'id' => get_the_ID(),
				'title' => get_the_title(),
				'content' => get_the_content()
			]
		];
	}

}
