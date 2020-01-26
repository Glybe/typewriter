<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino;

use Cappuccino\Cache\FilesystemCache;
use function str_replace;
use const TypeWriter\ROOT;

/**
 * Class CappuccinoCache
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino
 * @since 1.0.0
 */
final class CappuccinoCache extends FilesystemCache
{

	/**
	 * CappuccinoLatteCache constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct(ROOT . '/cache/cappuccino');
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function write(string $key, string $content): void
	{
		$content = str_replace('\\t', '', $content);

		parent::write($key, $content);
	}

}
