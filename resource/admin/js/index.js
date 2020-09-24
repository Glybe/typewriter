import "../css/index.scss";

import {name, version} from "../../../package";
import {Gallery, MetaFields, PostThumbnail, Relation} from "./feature";

function init()
{
	window.tw = {
		feature: {Gallery, MetaFields, PostThumbnail, Relation},
		name,
		version
	};
}

init();
