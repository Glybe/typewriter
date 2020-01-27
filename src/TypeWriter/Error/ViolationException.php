<?php
declare(strict_types=1);

namespace TypeWriter\Error;

/**
 * Class ViolationException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error
 * @since 1.0.0
 */
class ViolationException extends TypeWriterException
{

	public const ERR_TOO_LATE = 1;
	public const ERR_INVALID_PARAMETER = 2;

}
