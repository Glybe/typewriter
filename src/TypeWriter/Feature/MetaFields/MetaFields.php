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

use TypeWriter\Facade\Hooks;
use TypeWriter\Feature\Feature;
use TypeWriter\Util\AdminUtil;
use function get_current_screen;

/**
 * Class MetaFields
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Feature\MetaFields
 * @since 1.0.0
 */
abstract class MetaFields extends Feature
{

    private string $id;
    private string $description;
    private string $label;
    private string $metaKey;

    private array $fields = [];

    /**
     * MetaFields constructor.
     *
     * @param string $id
     * @param string $metaKey
     * @param string $label
     * @param string $description
     *
     * @author Bas Milius <bas@ideemedia.nl>
     * @since 1.0.0
     */
    public function __construct(string $id, string $metaKey, string $label, string $description = '')
    {
        parent::__construct(static::class);

        $this->id = $id;
        $this->description = $description;
        $this->label = $label;
        $this->metaKey = $metaKey;

        $this->registerFields();
        $this->registerMeta();

        Hooks::action('tw.admin-scripts.body', [$this, 'onAdminScriptsBody']);
    }

    /**
     * Invoked on tw.admin-scripts.body filter hook.
     * Loads the JS part of our meta fields editor.
     *
     * @param string[] $scripts
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminScriptsBody(array $scripts): array
    {
        $screen = get_current_screen();

        if (!AdminUtil::isGutenbergView() || !$this->isPostTypeSupported($screen->post_type))
            return $scripts;

        $fieldsJson = json_encode($this->fields);

        $scripts[] = <<<CODE
			new tw.feature.MetaFields("{$this->id}", "{$this->metaKey}", "{$this->label}", "{$this->description}", {$fieldsJson});
		CODE;

        return $scripts;
    }

    /**
     * Registers the given field.
     *
     * @param MetaField $field
     *
     * @return $this
     * @author Bas Milius <bas@ideemedia.nl>
     * @since 1.0.0
     */
    protected function register(MetaField $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Registers the meta field.
     *
     * @author Bas Milius <bas@ideemedia.nl>
     * @since 1.0.0
     */
    private function registerMeta(): void
    {
        $properties = [];

        /** @var MetaField $field */
        foreach ($this->fields as $field)
            $properties[$field->getMetaKey()] = ['type' => $field->getValueType()];

        register_meta('post', $this->metaKey, [
            'single' => true,
            'show_in_rest' => [
                'schema' => [
                    'type' => 'object',
                    'properties' => $properties
                ]
            ],
            'description' => 'TypeWriter MetaFields data.',
            'type' => 'object'
        ]);
    }

    /**
     * Registers all the fields in this meta box.
     *
     * @author Bas Milius <bas@ideemedia.nl>
     * @since 1.0.0
     */
    protected abstract function registerFields(): void;

}
