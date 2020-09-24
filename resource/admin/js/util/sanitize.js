/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

import {decodeEntities} from "@wordpress/html-entities";

export function stripTags(html)
{
	return decodeEntities(html.replace(/(<([^>]+)>)/gi, ""));
}
