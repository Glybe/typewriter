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

namespace TypeWriter\Feature\MetaFields;

/**
 * Class TextControlMetaField
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
class TextControlMetaField extends MetaField
{

    /**
     * TextControlMetaField constructor.
     *
     * @param string $metaKey
     * @param string $label
     * @param string $placeholder
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $metaKey, string $label, string $placeholder)
    {
        parent::__construct($metaKey, $label, 'TextControl', 'string');

        $this->setAttribute('placeholder', $placeholder);
    }

}
