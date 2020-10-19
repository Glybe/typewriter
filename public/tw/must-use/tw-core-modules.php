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

use TypeWriter\Module\Core\AdminMenuModule;
use TypeWriter\Module\Core\AdminModule;
use TypeWriter\Module\Core\APIModule;
use TypeWriter\Module\Core\BrandingModule;
use TypeWriter\Module\Core\DisableAdminFeaturesModule;
use TypeWriter\Module\Core\DisableCommentsAndPingsModule;
use TypeWriter\Module\Core\DisableCoreFeaturesModule;
use TypeWriter\Module\Core\DisableWPFeaturesModule;
use TypeWriter\Module\Core\ImproveOutputModule;
use TypeWriter\Module\Core\LoginModule;
use TypeWriter\Module\Core\PostTemplatesLoaderModule;
use TypeWriter\Module\Core\PostTemplatesResolverModule;
use TypeWriter\Module\Core\ThemeBaseModule;
use function TypeWriter\tw;

tw()->loadModule(APIModule::class);
tw()->loadModule(BrandingModule::class);
tw()->loadModule(DisableCoreFeaturesModule::class);
tw()->loadModule(DisableCommentsAndPingsModule::class);
tw()->loadModule(ThemeBaseModule::class);

if (tw()->isAdmin() || tw()->isApi()) {
    tw()->loadModule(AdminModule::class);
    tw()->loadModule(AdminMenuModule::class);
    tw()->loadModule(DisableAdminFeaturesModule::class);
    tw()->loadModule(PostTemplatesLoaderModule::class);
}

if (tw()->isFront()) {
    tw()->loadModule(DisableWPFeaturesModule::class);
    tw()->loadModule(ImproveOutputModule::class);
    tw()->loadModule(PostTemplatesResolverModule::class);
}

if (tw()->isLogin()) {
    tw()->loadModule(LoginModule::class);
}
