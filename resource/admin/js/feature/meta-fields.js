import {TextControl, TextareaControl} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {PluginDocumentSettingPanel} from "@wordpress/edit-post";
import {registerPlugin} from "@wordpress/plugins";

// noinspection JSUnusedLocalSymbols
const __mock = {
	control_type: "",
	meta_key: ""
};

const fieldRenderers = {

	TextControl: (field, onUpdate, metaFields) =>
	{
		return (
			<TextControl
				value={metaFields[field.meta_key]}
				label={field.label}
				placeholder={field.placeholder}
				onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
		);
	},

	TextareaControl: (field, onUpdate, metaFields) =>
	{
		return (
			<TextareaControl
				value={metaFields[field.meta_key]}
				label={field.label}
				placeholder={field.placeholder}
				onChange={value => onUpdate(field.meta_key, value, metaFields)}/>
		);
	}

};

function MetaFieldsComponent(props)
{
	const fields = props.fields.map(field => fieldRenderers[field.control_type](field, props.onUpdate, props.metaFields));

	return (

		<PluginDocumentSettingPanel title={props.label}>
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
			description={this.#description}
			label={this.#label}
			fields={this.#fields}/>;
	}

}
