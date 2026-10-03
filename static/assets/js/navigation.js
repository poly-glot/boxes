const disclosures = [...document.querySelectorAll("[data-disclosure]")];

const openDisclosures = () => disclosures.filter((disclosure) => disclosure.open);

const close = (disclosure, { returnFocus = false } = {}) => {
    disclosure.open = false;

    if (returnFocus) {
        disclosure.querySelector("summary")?.focus();
    }
};

document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
        return;
    }

    openDisclosures().forEach((disclosure) => close(disclosure, { returnFocus: disclosure.contains(document.activeElement) }));
});

document.addEventListener("click", (event) => {
    openDisclosures()
        .filter((disclosure) => !disclosure.contains(event.target))
        .forEach((disclosure) => close(disclosure));
});

disclosures.forEach((disclosure) => {
    disclosure.addEventListener("focusout", (event) => {
        if (!event.relatedTarget || disclosure.contains(event.relatedTarget)) {
            return;
        }

        close(disclosure);
    });
});

document.querySelectorAll("[data-print]").forEach((button) => {
    button.hidden = false;
    button.addEventListener("click", () => window.print());
});
