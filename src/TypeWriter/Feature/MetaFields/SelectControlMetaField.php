<?php
declare(strict_types=1);

namespace TypeWriter\Feature\MetaFields;

/**
 * Class SelectControlMetaField
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
class SelectControlMetaField extends MetaField
{

    /**
     * SelectControlMetaField constructor.
     *
     * @param string $metaKey
     * @param string $label
     * @param array $options
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $metaKey, string $label, array $options)
    {
        parent::__construct($metaKey, $label, 'SelectControl', 'string');

        $this->setAttribute('options', $options);
    }

}
