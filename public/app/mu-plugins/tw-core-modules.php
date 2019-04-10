<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

/*
 * Plugin Name: TypeWriter Core Modules
 * Description: Loads the core modules of TypeWriter. Without it TypeWriter will not run.
 * Author: Bas Milius
 * Author URI: https://bas.dev
 */

declare(strict_types=1);

use TypeWriter\Module\TW\TypeWriterAdminMenuModule;
use TypeWriter\Module\WP\AdminDesignModule;
use TypeWriter\Module\WP\APIModule;
use TypeWriter\Module\WP\DisableCommentsAndPingsModule;
use TypeWriter\Module\WP\DisableWPFeaturesModule;
use TypeWriter\Module\WP\ImproveOutputModule;
use TypeWriter\Module\WP\PostTemplatesLoaderModule;
use function TypeWriter\tw;

tw()->loadModule(APIModule::class);
tw()->loadModule(DisableCommentsAndPingsModule::class);

if (tw()->isAdmin())
{
//	tw()->loadModule(AdminDesignModule::class);
	tw()->loadModule(PostTemplatesLoaderModule::class);
	tw()->loadModule(TypeWriterAdminMenuModule::class);
}

if (tw()->isFront())
{
	tw()->loadModule(DisableWPFeaturesModule::class);
	tw()->loadModule(ImproveOutputModule::class);
}
