/*
 * Copyright (c) 2019 - IdeeMedia <info@ideemedia.nl>
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

import devConfig from "./rollup.config.dev";
import prodConfig from "./rollup.config.prod";

export default cla => cla.configDebug === true ? devConfig : prodConfig;
