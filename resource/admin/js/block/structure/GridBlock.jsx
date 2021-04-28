// noinspection DuplicatedCode

import {BlockControls, InnerBlocks, InspectorControls, useBlockProps} from "@wordpress/block-editor";
import {registerBlockType} from "@wordpress/blocks";
import {BaseControl, Button, ButtonGroup, PanelBody, RangeControl} from "@wordpress/components";
import {Fragment, useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {columnsIcon} from "../../icon/features.jsx";

export default function register()
{
    let preview = "lg";

    const setPreview = bp =>
    {
        preview = bp;
        window.dispatchEvent(new CustomEvent("tw_update_grid"));
    };

    function getColumnClasses(attributes, withPreview = false)
    {
        const classes = [`col-${attributes.xs}`];

        if (withPreview || (attributes.sm && attributes.sm !== attributes.xs)) classes.push(`sm:col-${attributes.sm}`);
        if (withPreview || (attributes.md && attributes.md !== attributes.sm)) classes.push(`md:col-${attributes.md}`);
        if (withPreview || (attributes.lg && attributes.lg !== attributes.md)) classes.push(`lg:col-${attributes.lg}`);
        if (withPreview || (attributes.xl && attributes.xl !== attributes.lg)) classes.push(`xl:col-${attributes.xl}`);

        return classes;
    }

    function getColumnValues(attributes, override = {}, fallThrough = true)
    {
        const result = {};

        result.xs = attributes.xs || 6;
        result.sm = attributes.sm && attributes.sm !== attributes.xs ? attributes.sm : (fallThrough ? result.xs : null);
        result.md = attributes.md && attributes.md !== attributes.sm ? attributes.md : (fallThrough ? result.sm : null);
        result.lg = attributes.lg && attributes.lg !== attributes.md ? attributes.lg : (fallThrough ? result.md : null);
        result.xl = attributes.xl && attributes.xl !== attributes.lg ? attributes.xl : (fallThrough ? result.lg : null);

        return Object.assign(result, override);
    }

    registerBlockType("tw/structure-column", {
        title: __("Column", "tw"),
        description: __("This block is used as a column wrapper in Grid.", "tw"),
        category: "tw-structure",
        icon: columnsIcon(),
        parent: [
            "tw/structure-grid"
        ],
        supports: {
            html: false
        },
        attributes: {
            xs: {default: 6, type: "integer"},
            sm: {default: null, type: "integer"},
            md: {default: null, type: "integer"},
            lg: {default: null, type: "integer"},
            xl: {default: null, type: "integer"}
        },

        edit({attributes, setAttributes})
        {
            const [, setRerenderState] = useState();
            const cols = getColumnValues(attributes);
            const blockProps = useBlockProps({className: [attributes.className || null, "tw-block-column", `preview-${preview}`, ...getColumnClasses(cols, true)].filter(c => !!c).join(" ")});

            useEffect(() =>
            {
                const onUpdateGrid = () => setRerenderState({preview});

                window.addEventListener("tw_update_grid", onUpdateGrid);

                return () => window.removeEventListener("tw_update_grid", onUpdateGrid);
            });

            return (
                    <Fragment>
                        <InspectorControls>
                            <PanelBody title={__("Settings", "tw")}>
                                <BaseControl label={__("Preview", "tw")}>
                                    <div style={{height: 6}}/>
                                    <ButtonGroup>
                                        <Button isPrimary={preview === "xs"} isSecondary={preview !== "xs"} onClick={() => setPreview("xs")}>XS</Button>
                                        <Button isPrimary={preview === "sm"} isSecondary={preview !== "sm"} onClick={() => setPreview("sm")}>SM</Button>
                                        <Button isPrimary={preview === "md"} isSecondary={preview !== "md"} onClick={() => setPreview("md")}>MD</Button>
                                        <Button isPrimary={preview === "lg"} isSecondary={preview !== "lg"} onClick={() => setPreview("lg")}>LG</Button>
                                        <Button isPrimary={preview === "xl"} isSecondary={preview !== "xl"} onClick={() => setPreview("xl")}>XL</Button>
                                    </ButtonGroup>
                                </BaseControl>

                                <RangeControl
                                        max={12}
                                        min={1}
                                        value={cols.xs}
                                        label={__("Column size", "tw") + " (XS)"}
                                        onChange={xs => setAttributes(getColumnValues(attributes, {xs}, false))}/>

                                <RangeControl
                                        max={12}
                                        min={1}
                                        value={cols.sm}
                                        label={__("Column size", "tw") + " (SM)"}
                                        onChange={sm => setAttributes(getColumnValues(attributes, {sm}, false))}/>

                                <RangeControl
                                        max={12}
                                        min={1}
                                        value={cols.md}
                                        label={__("Column size", "tw") + " (MD)"}
                                        onChange={md => setAttributes(getColumnValues(attributes, {md}, false))}/>

                                <RangeControl
                                        max={12}
                                        min={1}
                                        value={cols.lg}
                                        label={__("Column size", "tw") + " (LG)"}
                                        onChange={lg => setAttributes(getColumnValues(attributes, {lg}, false))}/>

                                <RangeControl
                                        max={12}
                                        min={1}
                                        value={cols.xl}
                                        label={__("Column size", "tw") + " (XL)"}
                                        onChange={xl => setAttributes(getColumnValues(attributes, {xl}, false))}/>
                            </PanelBody>
                        </InspectorControls>

                        <div {...blockProps}>
                            <InnerBlocks
                                    orientation="vertical"
                                    template={[["core/paragraph", {placeholder: "Placeholder"}]]}/>
                        </div>
                    </Fragment>
            );
        },

        save({attributes})
        {
            const blockProps = useBlockProps.save({className: getColumnClasses(attributes).join(" ")});

            return (
                    <div {...blockProps}>
                        <InnerBlocks.Content/>
                    </div>
            );
        }
    });

    registerBlockType("tw/structure-grid", {
        title: __("Grid", "tw"),
        description: __("Create responsive grids.", "tw"),
        category: "tw-structure",
        icon: columnsIcon(),
        supports: {
            html: false
        },
        attributes: {},

        edit({attributes, setAttributes})
        {
            const blockProps = useBlockProps({className: "tw-block-grid"});

            return (
                    <div {...blockProps} style={{marginBottom: 15}}>
                        <BlockControls/>
                        <InnerBlocks
                                allowedBlocks={["tw/structure-column"]}
                                orientation="horizontal"
                                template={[["tw/structure-column"], ["tw/structure-column"]]}/>
                    </div>
            );
        },

        save({attributes})
        {
            const blockProps = useBlockProps.save({className: "row"});

            return (
                    <div {...blockProps}>
                        <InnerBlocks.Content/>
                    </div>
            );
        }
    });
}
