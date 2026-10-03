const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

const initialise = (carousel, carouselIndex) => {
    const track = carousel.querySelector("[data-carousel-track]");
    const slides = [...track.querySelectorAll("[data-carousel-slide]")];
    const dots = [...carousel.querySelectorAll("[data-carousel-dot]")];
    const previous = carousel.querySelector("[data-carousel-previous]");
    const next = carousel.querySelector("[data-carousel-next]");

    if (slides.length < 2) {
        return;
    }

    track.id ||= `carousel-track-${carouselIndex + 1}`;

    [previous, next].forEach((button) => {
        button.hidden = false;
        button.setAttribute("aria-controls", track.id);
    });

    const step = () => slides[1].offsetLeft - slides[0].offsetLeft || track.clientWidth || 1;
    const currentIndex = () => Math.round(track.scrollLeft / step());

    const goTo = (index) => {
        const target = Math.min(Math.max(index, 0), slides.length - 1);

        track.scrollTo({ behavior: prefersReducedMotion.matches ? "auto" : "smooth", left: target * step() });
    };

    const updateControls = () => {
        const active = currentIndex();

        dots.forEach((dot, index) => dot.setAttribute("aria-current", String(index === active)));
        previous.setAttribute("aria-disabled", String(active === 0));
        next.setAttribute("aria-disabled", String(active === slides.length - 1));
    };

    previous.addEventListener("click", () => goTo(currentIndex() - 1));
    next.addEventListener("click", () => goTo(currentIndex() + 1));

    dots.forEach((dot, index) => {
        dot.addEventListener("click", (event) => {
            event.preventDefault();
            goTo(index);
        });
    });

    track.addEventListener("scroll", updateControls, { passive: true });
    window.addEventListener("resize", updateControls);
    updateControls();
};

document.querySelectorAll("[data-carousel]").forEach(initialise);
