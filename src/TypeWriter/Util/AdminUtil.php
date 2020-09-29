<?php
/**
 * Copyright (c) 2020 - IdeeMedia <info@ideemedia.nl>
 * Copyright (c) 2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Util;

use function in_array;

/**
 * Class AdminUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Util
 * @since 1.0.0
 */
final class AdminUtil
{

    /**
     * Returns TRUE if the given view is a view with the Gutenberg Editor.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isGutenbergView(): bool
    {
        global $pagenow;

        return in_array($pagenow, ['post-new.php', 'post.php', 'media-upload-popup']);
    }

}
