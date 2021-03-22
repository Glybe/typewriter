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
 * Class RangeControlMetaField
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
class RangeControlMetaField extends MetaField
{

    /**
     * RangeControlMetaField constructor.
     *
     * @param string $metaKey
     * @param string $label
     * @param int $min
     * @param int $max
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $metaKey, string $label, int $min = 0, int $max = 10)
    {
        parent::__construct($metaKey, $label, 'RangeControl', 'integer');

        $this->setAttribute('max', $max);
        $this->setAttribute('min', $min);
    }

}
