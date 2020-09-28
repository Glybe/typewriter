<?php
declare(strict_types=1);

namespace TypeWriter\Error;

/**
 * Class TemplateException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error
 * @since 1.0.0
 */
class TemplateException extends TypeWriterException
{

    public const ERR_TEMPLATE_FILE_NOT_FOUND = 1;

}
