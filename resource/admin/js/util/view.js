export function getPostId()
{
	const field = document.querySelector("#post_ID");

	if (!field)
		return null;

	return parseInt(field.value);
}
