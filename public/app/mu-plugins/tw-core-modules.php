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

use TypeWriter\Module\TW\BrandingModule;
use TypeWriter\Module\TW\AdminMenuModule;
use TypeWriter\Module\WP\APIModule;
use TypeWriter\Module\WP\DisableAdminFeaturesModule;
use TypeWriter\Module\WP\DisableCommentsAndPingsModule;
use TypeWriter\Module\WP\DisableWPFeaturesModule;
use TypeWriter\Module\WP\ImproveOutputModule;
use TypeWriter\Module\WP\PostTemplatesLoaderModule;
use function TypeWriter\tw;

tw()->loadModule(APIModule::class);
tw()->loadModule(BrandingModule::class);
tw()->loadModule(DisableCommentsAndPingsModule::class);

if (tw()->isAdmin())
{
	tw()->loadModule(AdminMenuModule::class);
	tw()->loadModule(DisableAdminFeaturesModule::class);
	tw()->loadModule(PostTemplatesLoaderModule::class);
}

if (tw()->isFront())
{
	tw()->loadModule(DisableWPFeaturesModule::class);
	tw()->loadModule(ImproveOutputModule::class);
}
