import {Button, Fill, Notice, PanelBody, PanelRow, Slot, Spinner} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {PluginSidebar, PluginSidebarMoreMenuItem} from "@wordpress/edit-post";
import {Fragment, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {registerPlugin} from "@wordpress/plugins";
import {useSelectPostDialog} from "../component/SelectPostDialog.jsx";
import {useSortableList} from "../hoc/SortableList.jsx";
import {stripTags} from "../util/sanitize";
import {linkIcon} from "../icon/features.jsx";

let didInitializePlugin = false;
let relations = null;
let selectPostDialog = null;
let sortableList = null;

function initializePlugin()
{
    if (didInitializePlugin)
        return;

    didInitializePlugin = true;
    relations = new Relations();
    selectPostDialog = useSelectPostDialog();
    sortableList = useSortableList();
}

export class Relations
{

    constructor()
    {
        registerPlugin("tw-relations", {
            name: "TypeWriter: Relations",
            icon: linkIcon(),
            render: () => this.render()
        });
    }

    render()
    {
        return (
                <Fragment>
                    <PluginSidebarMoreMenuItem target="tw-relations" icon={linkIcon()}>
                        {__("Relation Manager", "tw")}
                    </PluginSidebarMoreMenuItem>

                    <PluginSidebar name="tw-relations" icon={linkIcon()} title={__("Relations", "tw")}>
                        <PanelBody title={null} initialOpen={true}>
                            {__("Here are the relationships defined for the object. You can link other objects to this one from here.", "tw")}
                        </PanelBody>
                        <Slot name="tw-relations"/>
                    </PluginSidebar>
                </Fragment>
        );
    }

}

export class Relation
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

        registerPlugin(`tw-relations-${id}`, {
            name: "",
            icon: "",
            render: () => this.render(ComposedComponent)
        });
    }

    compose()
    {
        const applyWithDispatch = withDispatch((dispatch, {meta}) =>
        {
            const {editPost} = dispatch("core/editor");
            const {createNotice} = dispatch("core/notices");

            return {

                onObjectAdded: (objectIds, id) =>
                {
                    if (objectIds.includes(id))
                    {
                        createNotice("info", __("The selected object is already added.", "tw"), {
                            isDismissible: true,
                            type: "snackbar"
                        });

                        return;
                    }

                    editPost({
                        meta: {
                            ...meta,
                            [this.#metaKey]: [...objectIds, id]
                        }
                    });
                },

                onObjectDeleted: (objectIds, removeId) =>
                {
                    editPost({
                        meta: {
                            ...meta,
                            [this.#metaKey]: objectIds
                                    .filter(id => id !== removeId)
                        }
                    });
                },

                onObjectSorted: (objectIds, oldIndex, newIndex) =>
                {
                    editPost({
                        meta: {
                            ...meta,
                            [this.#metaKey]: sortableList.arrayMove(objectIds, oldIndex, newIndex)
                        }
                    });
                }

            };
        });

        const applyWithSelect = withSelect(select =>
        {
            const {getEntityRecords, getPostType} = select("core");
            const {getCurrentPostId, getEditedPostAttribute} = select("core/editor");

            const objectIds = getEditedPostAttribute("meta")[this.#metaKey] || [];
            const objects = getEntityRecords("postType", this.#foreignType, {
                includes: [...objectIds]
                        .sort()
                        .join(","),
                per_page: -1
            });

            return {
                currentPostId: getCurrentPostId(),
                objects: !objects ? null : objectIds
                        .map(id => objects.find(o => o.id === id))
                        .filter(o => !!o)
                        .map(o => ({
                            id: o.id,
                            title: stripTags(o.title.rendered)
                        })),
                objectIds: objectIds,
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
        const [isOpen, setOpen] = useState(false);

        const onSelect = id =>
        {
            setOpen(false);

            props.onObjectAdded(props.objectIds, id);
        };

        return (
                <Fill name="tw-relations">
                    <PanelBody title={this.#label}>
                        {props.objects === null && (
                                <Spinner/>
                        )}

                        {props.objects !== null && props.objects.length > 0 && (
                                <PanelRow>
                                    <sortableList.SortableList
                                            items={props.objects}
                                            onDelete={({id}) => props.onObjectDeleted(props.objectIds, id)}
                                            onSortEnd={({oldIndex, newIndex}) => props.onObjectSorted(props.objectIds, oldIndex, newIndex)}
                                            lockAxis="y"
                                            useDragHandle/>
                                </PanelRow>
                        )}

                        {props.objects !== null && props.objects.length === 0 && (
                                <div style={{margin: "0 -15px"}}>
                                    <Notice status="warning" isDismissible={false}>
                                        {__("There are no objects in this relationship.", "tw")}
                                    </Notice>
                                </div>
                        )}

                        <PanelRow>
                            <Button
                                    isSecondary
                                    isSmall
                                    onClick={() => setOpen(true)}>
                                {__("Add object", "tw")}
                            </Button>
                        </PanelRow>

                        {isOpen && (
                                <selectPostDialog.SelectPostDialog
                                        postType={this.#foreignType}
                                        onRequestClose={() => setOpen(false)}
                                        onSelect={onSelect}/>
                        )}
                    </PanelBody>
                </Fill>
        );
    }

}
