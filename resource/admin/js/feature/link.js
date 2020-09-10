import {Button, Fill, Modal, PanelBody, PanelRow, Slot} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {PluginSidebar, PluginSidebarMoreMenuItem} from "@wordpress/edit-post";
import {Fragment, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {registerPlugin} from "@wordpress/plugins";

let didInitializePlugin = false;
let links = null;

function initializePlugin()
{
	if (didInitializePlugin)
		return;

	didInitializePlugin = true;
	links = new Links();
}

export class Links
{

	constructor()
	{
		registerPlugin("tw-links", {
			icon: "admin-links",
			render: () => this.render()
		});
	}

	render()
	{
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="tw-link" icon="admin-links">
					{__("Link Manager", "tw")}
				</PluginSidebarMoreMenuItem>

				<PluginSidebar name="tw-link" icon="admin-links" title={__("Links", "tw")}>
					<PanelBody title={null} initialOpen={true}>
						{__("These are the objects linked to this one.", "tw")}
					</PanelBody>
					<Slot name="tw-links"/>
				</PluginSidebar>
			</Fragment>
		);
	}

}

export class Link
{

	#id;
	#label;
	#metaKey;
	#foreignType;

	constructor(id, label, metaKey, foreignType)
	{
		this.#id = id;
		this.#label = label;
		this.#metaKey = metaKey;
		this.#foreignType = foreignType;

		initializePlugin();

		const ComposedComponent = this.compose();

		registerPlugin(`tw-links-${id}`, {
			icon: "",
			render: () => this.render(ComposedComponent)
		});
	}

	compose()
	{
		const applyWithDispatch = withDispatch((dispatch, {meta}) =>
		{
			return {};
		});

		const applyWithSelect = withSelect(select =>
		{
			const {getPostType} = select("core");
			const {getCurrentPostId, getEditedPostAttribute} = select("core/editor");

			return {
				currentPostId: getCurrentPostId(),
				postType: getPostType(getEditedPostAttribute("type"))
			};
		});

		return compose(
			applyWithDispatch,
			applyWithSelect
		)(props => this.renderComponent(props));
	}

	render(ComposedComponent)
	{
		return (
			<ComposedComponent/>
		);
	}

	renderComponent(props)
	{
		const {} = props;

		const [isOpen, setOpen] = useState(false);

		return (
			<Fill name="tw-links">
				<PanelBody title={this.#label}>
					<PanelRow>
						<Button isPrimary onClick={() => setOpen(true)}>Toggle Modal</Button>
					</PanelRow>

					{isOpen && (
						<Modal title={__("Edit relations", "tw")} onRequestClose={() => setOpen(false)}>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid asperiores, at aut blanditiis cupiditate eius enim expedita facere laboriosam maxime nisi nobis odit officia, quisquam quo repellendus rerum. Cumque, quidem?
						</Modal>
					)}
				</PanelBody>
			</Fill>
		);
	}

}
