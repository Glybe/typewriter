<?php
declare(strict_types=1);

namespace TypeWriter\Storage;

use Columba\Database\Dao\AbstractModel;

/**
 * Class KeyValueStorage
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Storage
 * @since 1.0.0
 */
class KeyValueStorage extends AbstractModel
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
	}

}
