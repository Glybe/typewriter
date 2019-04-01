<?php
declare(strict_types=1);

use TypeWriter\Facade\Hooks;
use function TypeWriter\tw;

tw()->getRouter()->get('/test', function (): string
{
	return 'Hi from router!';
});

Hooks::filter('style_loader_tag', function (string $tag, string $handle, string $href, string $media): string
{
	return sprintf('<link rel="stylesheet" href="%s" id="%s-id" media="%s" type="text/css" />', $href, $handle, $media);
});
