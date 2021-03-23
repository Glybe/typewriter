import {default as registerSeo} from "./seo";
import {default as registerStructure} from "./structure";

export default function register() {
    registerSeo();
    registerStructure();
}
