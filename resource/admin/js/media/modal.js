const defaultOptions = {
	callback: () => undefined,
	selector: ""
};

export class MediaModal
{

	#frame;
	#settings;

	constructor(options)
	{
		this.#settings = Object.assign({}, defaultOptions, options);

		if (this.#settings.selector === "")
			throw new Error("A selector must be defined.");

		this.attachEvents();
	}

	attachEvents()
	{
		document.querySelector(this.#settings.selector)
			.addEventListener("click", evt => this.onClick(evt));
	}

	onClick(evt)
	{
		const {target} = evt;

		evt.preventDefault();
		evt.stopPropagation();

		this.#frame = wp.media({
			title: target.dataset.uploader_title,
			button: {text: target.dataset.uploader_button_text},
			library: {type: "image"}
		});

		this.#frame.on("open activate", () => this.onOpenActivate());
		this.#frame.on("select", () => this.onSelect());
		this.#frame.on("toolbar:create:select", () => this.onToolbarCreateSelect());

		this.#frame.open();
	}

	onOpenActivate()
	{
		const caller = document.querySelector(this.#settings.selector);

		if (caller.dataset.thumbnail_id)
		{
			const {Attachment} = wp.media.model;
			const selection = this.#frame.state().get("selection");
			selection.add(Attachment.get(caller.dataset.thumbnail_id));
		}
	}

	onSelect()
	{
		const attachment = this.#frame.state().get("selection").first().toJSON();

		this.#settings.callback(attachment);
	}

	onToolbarCreateSelect()
	{
		this.#frame.state().set("filterable", "uploaded");
	}

}
