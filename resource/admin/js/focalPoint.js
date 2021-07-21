export function initializeFocalPointSelector() {
    const selectors = Array.from(document.querySelectorAll(".tw-focal-point"));

    selectors
        .filter(selector => selector.dataset.fp === undefined)
        .forEach(selector => {
            const circle = selector.querySelector(".tw-focal-point-selector");
            const inputs = Array.from(selector.querySelectorAll("input"));
            let isMouseDown = false;

            const onMouseDown = evt => {
                isMouseDown = true;

                onMouseMove(evt);

                evt.preventDefault();
                evt.stopPropagation();
            };

            const onMouseUp = evt => {
                isMouseDown = false;

                window.jQuery(inputs[0]).change();

                evt.preventDefault();
                evt.stopPropagation();
            };

            const onMouseMove = evt => {
                if (!isMouseDown) {
                    return;
                }

                evt.preventDefault();
                evt.stopPropagation();

                const {top, left, height, width} = selector.getBoundingClientRect();
                const {clientX, clientY} = evt;

                const px = Math.max(10, Math.min(90, Math.round((clientX - left) / width * 100)));
                const py = Math.max(10, Math.min(90, Math.round((clientY - top) / height * 100)));

                circle.style.setProperty("top", `${py}%`);
                circle.style.setProperty("left", `${px}%`);

                inputs[0].value = px;
                inputs[1].value = py;
            };

            selector.addEventListener("mousedown", onMouseDown);
            selector.addEventListener("mouseup", onMouseUp);
            selector.addEventListener("mousemove", onMouseMove);
        });
}
