<?php
declare(strict_types=1);

namespace TypeWriter\Storage;

use Columba\Database\Model\Base;

/**
 * Class KeyValueStorage
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Storage
 * @since 1.0.0
 */
class KeyValueStorage extends Base
{

	/**
	 * KeyValueStorage constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct([]);

		static::$columns[static::class] = [];
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function prepare(array &$data): void
	{
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function publish(array &$data): void
	{
	}

}
