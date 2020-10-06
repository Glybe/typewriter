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

namespace TypeWriter\Feature;

use function in_array;

/**
 * Class Feature
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
abstract class Feature
{

    private string $name;

    /**
     * Feature constructor.
     *
     * @param string $name
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of the feature.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the supported post types or NULL when everything is supported.
     *
     * @return string[]|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function getSupportedPostTypes(): ?array
    {
        return null;
    }

    /**
     * Returns TRUE when the given post type is supported.
     *
     * @param string $postType
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function isPostTypeSupported(string $postType): bool
    {
        $supportedPostTypes = $this->getSupportedPostTypes();

        return $supportedPostTypes === null || in_array($postType, $supportedPostTypes);
    }

}
