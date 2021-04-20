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

use JsonSerializable;
use function array_merge;

/**
 * Class MetaField
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
abstract class MetaField implements JsonSerializable
{

    protected array $attributes = [];
    protected string $label;
    protected string $metaKey;

    private string $controlType;
    private string $valueType;

    /**
     * MetaField constructor.
     *
     * @param string $metaKey
     * @param string $label
     * @param string $controlType
     * @param string $valueType
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $metaKey, string $label, string $controlType, string $valueType)
    {
        $this->label = $label;
        $this->metaKey = $metaKey;

        $this->controlType = $controlType;
        $this->valueType = $valueType;
    }

    /**
     * Gets an attribute.
     *
     * @param string $name
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Gets the control type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getControlType(): string
    {
        return $this->controlType;
    }

    /**
     * Gets the label.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Gets the meta key.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getMetaKey(): string
    {
        return $this->metaKey;
    }

    /**
     * Gets the value type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getValueType(): string
    {
        return $this->valueType;
    }

    /**
     * Removes an attribute.
     *
     * @param string $name
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function removeAttribute(string $name): self
    {
        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function jsonSerialize(): array
    {
        return array_merge($this->attributes, [
            'control_type' => $this->controlType,
            'value_type' => $this->valueType,
            'label' => $this->label,
            'meta_key' => $this->metaKey
        ]);
    }

}
