<?php
declare(strict_types=1);

use function TypeWriter\tw;

tw()->getRouter()->get('/test', function (): string
{
	return 'Hi from router!';
});
