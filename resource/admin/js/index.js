import "../css/index.scss";

import { default as registerBlocks } from "./block";
import {initializeFocalPointSelector} from "./focalPoint";

const packageJson = {
    name: "@bybas/typewriter",
    version: "1.0.0"
};

async function init() {
    let features = {};

    if (typeof wp.element !== "undefined") {
        const {Gallery, MetaFields, PostThumbnail, Relation} = await import("./feature");

        features = {Gallery, MetaFields, PostThumbnail, Relation};
    }

    window.tw = {
        feature: features,
        focalPoint: initializeFocalPointSelector,
        name: packageJson.name,
        version: packageJson.version
    };
}

init().then();
registerBlocks();
