import "../css/index.scss";

import { name, version } from "../../../package";
import { Gallery, Link, MetaFields, PostThumbnail } from "./feature";

function init()
{
	window.tw = {
		feature: {Gallery, Link, MetaFields, PostThumbnail},
		name,
		version
	};
}

init();
