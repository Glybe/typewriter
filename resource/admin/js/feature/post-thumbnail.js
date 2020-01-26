export class PostThumbnail
{

	static setThumbnailHtml(id, postType, html)
	{
		const metaBox = document.querySelector(`#${postType}_${id}`);
		const inside = metaBox.querySelector(".inside");

		inside.innerHTML = html;
	}

	static setThumbnailId(id, postType, thumbnailId)
	{
		const field = document.querySelector(`input[value=_${postType}_${id}_thumbnail_id]`);

		if (!field)
			return;


	}

}

/*
	setThumbnailID: function (thumb_id, id, post_type)
	{
		const field = jQuery('input[value=_' + post_type + '_' + id + '_thumbnail_id]', '#list-table');
		if (field.size() > 0)
		{
			jQuery('#meta\\[' + field.attr('id').match(/[0-9]+/) + '\\]\\[value\\]').text(thumb_id);
		}
	},
 */
