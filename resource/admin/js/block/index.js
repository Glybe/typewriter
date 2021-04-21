import {default as registerSeo} from "./seo";
import {default as registerStructure} from "./structure";

export default function register() {
    if (typeof wp.blocks === "undefined")
        return;

    registerSeo();
    registerStructure();
}
