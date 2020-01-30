import "../css/index.scss";

import { name, version } from "../../../package";
import { Gallery, MetaFields, PostThumbnail } from "./feature";

function init()
{
	window.tw = {
		feature: {Gallery, MetaFields, PostThumbnail},
		name,
		version
	};
}

init();
