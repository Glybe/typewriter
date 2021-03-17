import {BlockControls} from "@wordpress/block-editor";
import {registerBlockType} from "@wordpress/blocks";
import {Button, Modal, Placeholder, TextareaControl} from "@wordpress/components";
import {Fragment, useMemo, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {bracketsCurlyIcon} from "../../icon/features.jsx";

export default function register()
{
    function isValidJsonLd(json, allowEmpty = false)
    {
        if (allowEmpty && (!json || json.trim() === ""))
            return true;

        try
        {
            const j = JSON.parse(json);

            if (Array.isArray(j) && j.length > 0)
            {
                for (let s of j)
                    if (!s["@type"])
                        return false;
            }
            else if (!j["@type"])
            {
                return false;
            }

            return true;
        } catch (err)
        {
            return false;
        }
    }

    registerBlockType("tw/seo-json-ld", {
        title: __("Structured data", "tw"),
        description: __("Allows you to add stuctured data to pages.", "tw"),
        category: "tw-seo",
        icon: bracketsCurlyIcon(),
        supports: {
            html: false
        },
        attributes: {
            json: {type: "string"}
        },

        edit({attributes, setAttributes})
        {
            const [isEditing, setEditing] = useState(false);
            const [json, setJson] = useState(attributes.json || "");

            const isValid = useMemo(() => isValidJsonLd(json, true), [json]);
            const isValidAttribute = useMemo(() => isValidJsonLd(attributes.json || ""), [attributes.json]);
            const objectName = useMemo(() =>
            {
                if (!isValidAttribute)
                    return "";

                const j = JSON.parse(attributes.json);

                if (Array.isArray(j))
                    return j.map(s => s["@type"]).join(", ");

                return j["@type"];
            }, [attributes.json]);

            return (
                    <Fragment>
                        {isEditing && (
                                <Modal
                                        focusOnMount={true}
                                        shouldCloseOnClickOutside={false}
                                        shouldCloseOnEsc={true}
                                        title={__("Edit Structured data", "tw")}
                                        onRequestClose={() =>
                                        {
                                            setEditing(false);
                                            setJson(attributes.json);
                                        }}>

                                    <div style={{width: 540, maxWidth: "100%"}}/>

                                    <TextareaControl
                                            help={__("Please do not edit this if you don't know what this does.", "tw")}
                                            rows={21}
                                            style={{fontFamily: "monospace"}}
                                            value={json}
                                            onChange={json => setJson(json)}/>

                                    <Button
                                            disabled={!isValid}
                                            isPrimary
                                            label={__("Close")}
                                            onClick={() =>
                                            {
                                                setAttributes({json});
                                                setEditing(false);
                                            }}>
                                        {__("Save")}
                                    </Button>

                                </Modal>
                        )}

                        <div className="wp-block" style={{marginBottom: 15}}>
                            <BlockControls/>

                            <Placeholder
                                    icon={bracketsCurlyIcon()}
                                    label={isValidAttribute ? `@type: ${objectName}` : __("Add Structured data", "tw")}
                                    instructions={__("Add Json-LD Structured data to the page. Structured data is used by Google to create rich snippiets in their search results.", "tw")}>

                                <Button
                                        isSecondary
                                        onClick={() => setEditing(true)}>
                                    {isValidAttribute ? __("Edit") : __("Add")}
                                </Button>

                            </Placeholder>
                        </div>
                    </Fragment>
            );
        },

        save({attributes})
        {
            if (!attributes.json || attributes.json.trim() === "")
                return null;

            return (
                    <script type="application/ld+json">
                        {JSON.stringify(JSON.parse(attributes.json))}
                    </script>
            );
        }
    });
}
