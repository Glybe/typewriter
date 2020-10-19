import "../css/index.scss";

// import {default as packageJson} from "../../../package";
import {Gallery, MetaFields, PostThumbnail, Relation} from "./feature";

const packageJson = {
    name: "@bybas/typewriter",
    version: "1.0.0"
};

function init() {
    window.tw = {
        feature: {Gallery, MetaFields, PostThumbnail, Relation},
        name: packageJson.name,
        version: packageJson.version
    };
}

init();
