import {BaseControl, RangeControl, TextareaControl, TextControl, ToggleControl} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {PluginDocumentSettingPanel} from "@wordpress/edit-post";
import {__} from "@wordpress/i18n";
import {registerPlugin} from "@wordpress/plugins";

// noinspection JSUnusedLocalSymbols
const __mock = {
    control_type: "",
    meta_key: ""
};

const fieldRenderers = {

    RangeControl: (field, onUpdate, metaFields) =>
    {
        return (
                <RangeControl
                        max={field.max || 10}
                        min={field.min || 0}
                        value={metaFields[field.meta_key] || field.min || 0}
                        label={__(field.label, "tw")}
                        onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
        );
    },

    TextControl: (field, onUpdate, metaFields) =>
    {
        return (
                <TextControl
                        value={metaFields[field.meta_key] || ""}
                        label={__(field.label, "tw")}
                        placeholder={__(field.placeholder, "tw")}
                        onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
        );
    },

    TextareaControl: (field, onUpdate, metaFields) =>
    {
        return (
                <TextareaControl
                        value={metaFields[field.meta_key] || ""}
                        label={__(field.label, "tw")}
                        placeholder={__(field.placeholder, "tw")}
                        onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
        );
    },

    ToggleControl: (field, onUpdate, metaFields) =>
    {
        return (
                <BaseControl label={__(field.label, "tw")}>
                    <div style={{height: 6}}/>
                    <ToggleControl
                            checked={metaFields[field.meta_key] || false}
                            help={__(field.help, "tw")}
                            onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
                </BaseControl>
        );
    }

};

function MetaFieldsComponent(props)
{
    const fields = props.fields.map(field => fieldRenderers[field.control_type](field, props.onUpdate, props.metaFields || {}));

    return (

            <PluginDocumentSettingPanel name={props.id} title={props.label}>
                {props.description !== "" && <p dangerouslySetInnerHTML={{__html: props.description}}/>}
                {fields}
            </PluginDocumentSettingPanel>

    );
}

export class MetaFields
{

    #id;
    #description;
    #label;
    #metaKey;
    #fields;

    constructor(id, metaKey, label, description, fields)
    {
        this.#id = id;
        this.#description = description;
        this.#label = label;
        this.#metaKey = metaKey;
        this.#fields = fields;

        const ComposedComponent = this.compose();

        registerPlugin(`meta-fields-${this.#id}`, {
            name: "TypeWriter: Meta Fields",
            icon: "editor-quote",
            render: () => this.render(ComposedComponent)
        });
    }

    compose()
    {
        const metaKey = this.#metaKey;

        const applyWithDispatch = withDispatch((dispatch, {meta}) =>
        {
            const {editPost} = dispatch("core/editor");

            return {

                onUpdate(valueKey, value, metaFields)
                {
                    editPost({meta: {...meta, [metaKey]: {...metaFields, [valueKey]: value}}});
                }

            };
        });

        const applyWithSelect = withSelect(select =>
        {
            const {getEditedPostAttribute} = select("core/editor");

            return {
                metaFields: getEditedPostAttribute("meta")[metaKey] || {}
            };
        });

        return compose(
                applyWithDispatch,
                applyWithSelect
        )(MetaFieldsComponent);
    }

    render(ComposedComponent)
    {
        return <ComposedComponent
                id={this.#id}
                description={__(this.#description, "tw")}
                label={__(this.#label, "tw")}
                fields={this.#fields}/>;
    }

}
