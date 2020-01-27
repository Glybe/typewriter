// noinspection JSUnusedLocalSymbols
const __mock = {
	media_details: {
		source_url: ""
	}
};

function PostThumbnailComponent(props)
{
	const {Button, ResponsiveWrapper, Spinner} = wp.components;
	const {MediaUpload, MediaUploadCheck} = wp.blockEditor;
	const {PostFeaturedImageCheck} = wp.editor;
	const {PluginDocumentSettingPanel} = wp.editPost;
	const {applyFilters} = wp.hooks;
	const {__} = wp.i18n;
	const {has} = _;

	const instructions = <p>{__("To edit the featured image, you need permission to upload media.")}</p>;

	let mediaWidth, mediaHeight, mediaSourceUrl;

	if (props.media)
	{
		const mediaSize = applyFilters("editor.PostFeaturedImage.imageSize", "post-thumbnail", props.media.id, props.currentPostId);

		if (has(props.media, ["media_details", "sizes", mediaSize]))
		{
			mediaWidth = props.media.media_details.sizes[mediaSize].width;
			mediaHeight = props.media.media_details.sizes[mediaSize].height;
			mediaSourceUrl = props.media.media_details.sizes[mediaSize].source_url;
		}
		else
		{
			const fallbackMediaSize = applyFilters("editor.PostFeaturedImage.imageSize", "thumbnail", props.media.id, props.currentPostId);
			if (has(props.media, ["media_details", "sizes", fallbackMediaSize]))
			{
				mediaWidth = props.media.media_details.sizes[fallbackMediaSize].width;
				mediaHeight = props.media.media_details.sizes[fallbackMediaSize].height;
				mediaSourceUrl = props.media.media_details.sizes[fallbackMediaSize].source_url;
			}
			else
			{
				mediaWidth = props.media.media_details.width;
				mediaHeight = props.media.media_details.height;
				mediaSourceUrl = props.media.source_url;
			}
		}
	}

	return (

		<PluginDocumentSettingPanel title={props.label}>
			<PostFeaturedImageCheck>
				<div className="editor-post-featured-image">

					<MediaUploadCheck fallback={instructions}>
						<MediaUpload
							title={props.label}
							onSelect={props.onUpdateImage}
							allowedTypes="image"
							modalClass="editor-post-featured-image__media-modal"
							render={({open}) => (
								<Button
									className={!props.mediaId ? "editor-post-featured-image__toggle" : "editor-post-featured-image__preview"}
									onClick={open}
									aria-label={!props.mediaId ? null : __("Edit or update the image")}>
									{!!props.mediaId && props.media &&
									<ResponsiveWrapper
										naturalWidth={mediaWidth}
										naturalHeight={mediaHeight}
										isInline>
										<img src={mediaSourceUrl} alt=""/>
									</ResponsiveWrapper>
									}
									{!!props.mediaId && !props.media && <Spinner/>}
									{!props.mediaId && `Update Image`}
								</Button>
							)}
							value={props.mediaId}/>
					</MediaUploadCheck>

					{!!props.mediaId && props.media && !props.media.isLoading &&
					<MediaUploadCheck>
						<MediaUpload
							title={props.label}
							onSelect={props.onUpdateImage}
							allowedTypes="image"
							modalClass="editor-post-featured-image__media-modal"
							render={({open}) => (
								<Button onClick={open} isButton isLarge>
									{__("Replace Image")}
								</Button>
							)}/>
					</MediaUploadCheck>
					}

					{!!props.mediaId &&
					<MediaUploadCheck>
						<Button onClick={props.onRemoveImage} isLink isDestructive>
							{__("Remove Image")}
						</Button>
					</MediaUploadCheck>
					}

				</div>
			</PostFeaturedImageCheck>
		</PluginDocumentSettingPanel>

	);
}

export class PostThumbnail
{

	#id;
	#label;
	#metaKey;

	constructor(id, label, metaKey)
	{
		const {registerPlugin} = wp.plugins;

		this.#id = id;
		this.#label = label;
		this.#metaKey = metaKey;

		const ComposedComponent = this.compose();

		registerPlugin(`post-thumbnail-${this.#id}`, {
			render: () => this.render(ComposedComponent)
		});
	}

	compose()
	{
		const {withNotices} = wp.components;
		const {compose} = wp.compose;
		const {withDispatch, withSelect} = wp.data;

		const metaKey = this.#metaKey;

		const applyWithDispatch = withDispatch((dispatch, {meta}) =>
		{
			const {editPost} = dispatch("core/editor");

			return {

				onUpdateImage(media)
				{
					editPost({meta: {...meta, [metaKey]: media.id}});
				},

				onRemoveImage()
				{
					editPost({meta: {...meta, [metaKey]: 0}});
				}

			};
		});

		const applyWithSelect = withSelect(select =>
		{
			const {getMedia, getPostType} = select("core");
			const {getCurrentPostId, getEditedPostAttribute} = select("core/editor");

			const mediaId = getEditedPostAttribute("meta")[metaKey] || 0;

			return {
				media: mediaId ? getMedia(mediaId) : null,
				mediaId: mediaId,
				currentPostId: getCurrentPostId(),
				postType: getPostType(getEditedPostAttribute("type"))
			};
		});

		return compose(
			withNotices,
			applyWithDispatch,
			applyWithSelect
		)(PostThumbnailComponent);
	}

	render(ComposedComponent)
	{
		return <ComposedComponent id={this.#id} label={this.#label}/>;
	}

}
