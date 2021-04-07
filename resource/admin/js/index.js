import "../css/index.scss";

const packageJson = {
    name: "@bybas/typewriter",
    version: "1.0.0"
};

async function init() {
    let features = {};

    if (typeof wp.element !== "undefined") {
        const {default: registerBlocks} = await import("./block");
        const {Gallery, MetaFields, PostThumbnail, Relation} = await import("./feature");

        features = {Gallery, MetaFields, PostThumbnail, Relation};
        registerBlocks();
    }

    window.tw = {
        feature: features,
        name: packageJson.name,
        version: packageJson.version
    };
}

init().then();
