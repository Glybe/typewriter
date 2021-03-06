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
 * Class ToggleControlMetaField
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
class ToggleControlMetaField extends MetaField
{

    /**
     * ToggleControlMetaField constructor.
     *
     * @param string $metaKey
     * @param string $label
     * @param string $help
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $metaKey, string $label, string $help = '')
    {
        parent::__construct($metaKey, $label, 'ToggleControl', 'boolean');

        $this->setAttribute('help', $help);
    }

}
