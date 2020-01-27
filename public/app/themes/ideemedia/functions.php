<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Feature\IntroTextMetaFields;
use TypeWriter\Feature\PostThumbnail;
use function TypeWriter\tw;

Hooks::action('init', function (): void
{
	tw()->loadFeature(IntroTextMetaFields::class);

	PostThumbnail::add('page', 'overview', 'Overzichtsfoto');
});

Hooks::action('wp_enqueue_scripts', function (): void
{
	Dependencies::enqueueStyle('proxima-nova', 'https://font.mili.us/css?family=proxima-nova');
	Dependencies::enqueueStyle('latte', 'https://unpkg.com/@bybas/latte-ui@1.9.0-beta.18/dist/latte-ui.css');
});

tw()->getRouter()->get('/test', function (): string
{
	return tw()->getRouter()->render('test.cappy');
});
