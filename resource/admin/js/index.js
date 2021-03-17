import "../css/index.scss";

import {default as registerBlocks} from "./block";
import {Gallery, MetaFields, PostThumbnail, Relation} from "./feature";

const packageJson = {
    name: "@bybas/typewriter",
    version: "1.0.0"
};

function init() {
    let features = {};

    if (typeof wp.element !== "undefined") {
        features = {Gallery, MetaFields, PostThumbnail, Relation};
        registerBlocks();
    }

    window.tw = {
        feature: features,
        name: packageJson.name,
        version: packageJson.version
    };
}

init();
