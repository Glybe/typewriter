import {MediaUpload, MediaUploadCheck} from "@wordpress/block-editor";
import {Button, Fill, Notice, PanelBody, PanelRow, Slot} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {PluginSidebar, PluginSidebarMoreMenuItem} from "@wordpress/edit-post";
import {PostFeaturedImage} from "@wordpress/editor";
import {Fragment} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {registerPlugin} from "@wordpress/plugins";
import {galleryIcon} from "../icon/features.jsx";

let didInitializePlugin = false;
let galleries = null;

function initializePlugin()
{
    if (didInitializePlugin)
        return;

    didInitializePlugin = true;
    galleries = new Galleries();
}

export class Galleries
{

    constructor()
    {
        registerPlugin("tw-galleries", {
            name: "TypeWriter: Galleries",
            icon: galleryIcon(),
            render: () => this.render()
        });
    }

    render()
    {
        return (
                <Fragment>
                    <PluginSidebar name="tw-gallery" icon={galleryIcon()} title={__("Galleries", "tw")}>
                        <PanelBody title={null} initialOpen={true}>
                            {__("Here you can change the galleries supported by the selected template.", "tw")}
                        </PanelBody>
                        <Slot name="tw-galleries"/>
                    </PluginSidebar>

                    <PluginSidebarMoreMenuItem target="tw-gallery" icon={galleryIcon()}>
                        {__("Galleries Manager", "tw")}
                    </PluginSidebarMoreMenuItem>
                </Fragment>
        );
    }

}

export class Gallery
{

    #id;
    #label;
    #metaKey;

    constructor(id, label, metaKey)
    {
        this.#id = id;
        this.#label = label;
        this.#metaKey = metaKey;

        initializePlugin();

        const ComposedComponent = this.compose();

        registerPlugin(`tw-galleries-${id}`, {
            icon: "",
            render: () => this.render(ComposedComponent)
        });
    }

    compose()
    {
        const applyWithDispatch = withDispatch((dispatch, {meta}) =>
        {
            const {editPost} = dispatch("core/editor");

            return {

                onMediaSelected: (medias) =>
                {
                    editPost({meta: {...meta, [this.#metaKey]: medias.map(media => media.id)}});
                },

                onRemoveGallery: () =>
                {
                    editPost({meta: {...meta, [this.#metaKey]: []}});
                }

            };
        });

        const applyWithSelect = withSelect(select =>
        {
            const {getMedia, getPostType} = select("core");
            const {getCurrentPostId, getEditedPostAttribute} = select("core/editor");

            const mediaIds = getEditedPostAttribute("meta")[this.#metaKey] || [];

            return {
                medias: mediaIds
                        .map(mediaId => getMedia(mediaId))
                        .filter(media => !!media),
                mediaIds: mediaIds.length === 0 ? null : mediaIds,
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
        const {medias = [], mediaIds = []} = props;

        return (
                <Fill name="tw-galleries">
                    <PanelBody title={this.#label}>
                        {
                            medias.length > 0
                                    ? (
                                            <PanelRow>
                                                <div style={{display: "flex", margin: -2, flexFlow: "row wrap"}}>
                                                    {medias.map(this.renderMedia)}
                                                </div>
                                            </PanelRow>
                                    )
                                    : (
                                            <div style={{margin: "0 -15px"}}>
                                                <Notice status="warning" isDismissible={false}>
                                                    {__("This gallery appears to be empty.", "tw")}
                                                </Notice>
                                            </div>
                                    )
                        }
                        <PanelRow>
                            <MediaUploadCheck fallback={__("To edit this gallery, you need permissions to upload media.", "tw")}>
                                <MediaUpload
                                        title={this.#label}
                                        allowedTypes="image"
                                        gallery={true}
                                        multiple={true}
                                        value={mediaIds}
                                        onSelect={props.onMediaSelected}
                                        render={(({open}) => this.renderAddButton(open, medias.length === 0))}/>
                            </MediaUploadCheck>
                        </PanelRow>
                        {medias.length > 0 && (
                                <PanelRow>
                                    <Button onClick={props.onRemoveGallery} isLink isDestructive>
                                        {__("Remove gallery", "tw")}
                                    </Button>
                                </PanelRow>
                        )}
                    </PanelBody>
                </Fill>
        );
    }

    renderMedia(media)
    {
        let url;

        if (media.media_details.sizes.thumbnail)
        {
            url = media.media_details.sizes.thumbnail.source_url;
        }
        else if (media.media_details.sizes.medium)
        {
            url = media.media_details.sizes.medium.source_url;
        }

        if (!url)
        {
            console.log(media);

            return (
                    <div>¯\_(ツ)_/¯</div>
            );
        }

        return (
                <img src={url} height={57} width={57} alt={media.title.rendered} style={{margin: 2, objectFit: "cover", objectPosition: "center"}}/>
        );
    }

    renderAddButton(open, isEmpty)
    {
        return (
                <Button isSecondary isSmall onClick={open}>{__(isEmpty ? "Add media" : "Edit media", "tw")}</Button>
        );
    }

}
