const STORAGE_KEY = "boxes-basket";
const DELIVERY_FEE = 7.95;
const FREE_DELIVERY_FROM = 150;
const VAT_RATE = 0.2;
const MAX_QUANTITY = 999;

const pounds = new Intl.NumberFormat("en-GB", { currency: "GBP", style: "currency" });
const money = (amount) => pounds.format(amount);
const roundPence = (amount) => Math.round(amount * 100) / 100;

export const clampQuantity = (value) => {
    const quantity = Math.floor(Number(value));

    if (!Number.isFinite(quantity)) {
        return 1;
    }

    return Math.min(Math.max(quantity, 1), MAX_QUANTITY);
};

export const activeTier = (tiers, quantity) => tiers
    .filter((tier) => quantity >= tier.min)
    .reduce((best, tier) => (tier.min > best.min ? tier : best), tiers[0]);

export const addLine = (lines, sku, quantity) => {
    if (!lines.some((line) => line.sku === sku)) {
        return [...lines, { qty: clampQuantity(quantity), sku }];
    }

    return lines.map((line) => (line.sku === sku ? { qty: clampQuantity(line.qty + quantity), sku } : line));
};

export const basketTotals = (lines, productBySku) => {
    const priced = lines
        .filter((line) => productBySku.has(line.sku))
        .map((line) => {
            const product = productBySku.get(line.sku);
            const tier = activeTier(product.tiers, line.qty);
            const discounted = tier.price < product.price;

            return { ...line, discountFrom: discounted ? tier.min : null, product, total: roundPence(tier.price * line.qty), unit: tier.price };
        });

    const subtotal = roundPence(priced.reduce((sum, line) => sum + line.total, 0));
    const delivery = subtotal === 0 || subtotal >= FREE_DELIVERY_FROM ? 0 : DELIVERY_FEE;
    const vat = roundPence((subtotal + delivery) * VAT_RATE);

    return { delivery, lines: priced, subtotal, total: roundPence(subtotal + delivery + vat), vat };
};

const readLines = () => {
    try {
        const stored = JSON.parse(localStorage.getItem(STORAGE_KEY) ?? "[]");

        if (!Array.isArray(stored)) {
            return [];
        }

        return stored
            .filter((line) => typeof line?.sku === "string")
            .map((line) => ({ qty: clampQuantity(line.qty), sku: line.sku }));
    } catch {
        return [];
    }
};

const packCount = (lines) => lines.reduce((sum, line) => sum + line.qty, 0);

const updateBadge = (lines) => {
    const count = packCount(lines);
    const description = count === 0 ? ", empty" : `, ${count} ${count === 1 ? "item" : "items"}`;

    document.querySelectorAll("[data-basket-badge]").forEach((badge) => {
        badge.textContent = count > 99 ? "99+" : String(count);
        badge.hidden = count === 0;
    });

    document.querySelectorAll("[data-basket-summary]").forEach((summary) => {
        summary.textContent = description;
    });
};

const writeLines = (lines) => {
    updateBadge(lines);

    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(lines));
    } catch {
        return;
    }
};

const loadProducts = async () => {
    const response = await fetch("/assets/search-index.json");

    if (!response.ok) {
        throw new Error(`Search index returned ${response.status}`);
    }

    const index = await response.json();

    return new Map(index.products.map((product) => [product.sku, product]));
};

const addedMessage = (quantity, name) => `Added ${quantity} × ${name} to your basket`;

const handleAddToBasket = (event) => {
    const form = event.target.closest("form[data-sku]");

    if (!form) {
        return;
    }

    event.preventDefault();

    const quantity = clampQuantity(new FormData(form).get("qty"));
    const status = form.querySelector("[data-basket-status]");

    writeLines(addLine(readLines(), form.dataset.sku, quantity));

    if (status) {
        status.textContent = addedMessage(quantity, form.dataset.name);
    }
};

const handleDemoForm = (event) => {
    const confirmation = document.getElementById(event.target.dataset.demoForm ?? "");

    if (!confirmation) {
        return;
    }

    event.preventDefault();
    event.target.hidden = true;
    confirmation.hidden = false;
    confirmation.focus();
};

const handleOrderForm = (event) => {
    const linesField = event.target.querySelector("[data-basket-order-lines]");

    if (!linesField) {
        return;
    }

    linesField.value = JSON.stringify(readLines());
};

const basketParts = (root) => ({
    delivery: root.querySelector("[data-basket-delivery]"),
    deliveryNote: root.querySelector("[data-basket-delivery-note]"),
    empty: root.querySelector("[data-basket-empty]"),
    fallback: root.querySelector("[data-basket-fallback]"),
    filled: root.querySelector("[data-basket-filled]"),
    lines: root.querySelector("[data-basket-lines]"),
    live: root.querySelector("[data-basket-live]"),
    subtotal: root.querySelector("[data-basket-subtotal]"),
    template: root.querySelector("[data-basket-line]"),
    title: root.querySelector("#basket-title"),
    total: root.querySelector("[data-basket-total]"),
    vat: root.querySelector("[data-basket-vat]"),
});

const deliveryNote = (subtotal) => {
    if (subtotal >= FREE_DELIVERY_FROM) {
        return "Your order qualifies for free delivery.";
    }

    return `Spend ${money(FREE_DELIVERY_FROM - subtotal)} more ex VAT for free delivery.`;
};

const fillLine = (row, line) => {
    const tierHint = row.querySelector("[data-line-tier]");

    tierHint.hidden = !line.discountFrom;
    tierHint.textContent = line.discountFrom ? `Bulk price for ${line.discountFrom}+ packs` : "";
    row.querySelector("[data-line-price]").textContent = money(line.unit);
    row.querySelector("[data-line-total]").textContent = money(line.total);
    row.querySelector("[data-line-quantity]").value = String(line.qty);
};

const renderLine = (template, line) => {
    const row = template.content.firstElementChild.cloneNode(true);
    const { product } = line;
    const inputId = `basket-quantity-${product.slug}`;
    const image = row.querySelector("[data-line-image]");
    const link = row.querySelector("[data-line-link]");
    const label = row.querySelector("[data-line-label]");

    row.dataset.sku = product.sku;
    image.src = product.image.small;
    link.href = product.url;
    link.textContent = product.name;
    row.querySelector("[data-line-meta]").textContent = `SKU: ${product.sku} · ${product.pack}`;
    label.htmlFor = inputId;
    label.textContent = `Quantity for ${product.name}`;
    row.querySelector("[data-line-quantity]").id = inputId;
    row.querySelector("[data-line-remove-name]").textContent = ` ${product.name} from your basket`;
    fillLine(row, line);

    return row;
};

const initialiseBasketPage = async (root) => {
    const parts = basketParts(root);
    const params = new URLSearchParams(window.location.search);
    const pendingSku = params.get("add")?.trim().toUpperCase();
    const pendingQuantity = clampQuantity(params.get("qty") ?? 1);

    if (params.has("add")) {
        window.history.replaceState(null, "", window.location.pathname);
    }

    const products = await loadProducts().catch(() => null);

    if (!products) {
        parts.fallback.querySelector(".notice__title").textContent = "We could not load your basket";
        return;
    }

    parts.fallback.hidden = true;

    const announce = (message) => {
        parts.live.textContent = message;
    };

    const totalsFor = () => basketTotals(readLines(), products);

    const renderTotals = (totals) => {
        parts.subtotal.textContent = money(totals.subtotal);
        parts.delivery.textContent = totals.delivery === 0 ? "Free" : money(totals.delivery);
        parts.vat.textContent = money(totals.vat);
        parts.total.textContent = money(totals.total);
        parts.deliveryNote.textContent = deliveryNote(totals.subtotal);
    };

    const render = () => {
        const totals = totalsFor();
        const hasLines = totals.lines.length > 0;

        parts.empty.hidden = hasLines;
        parts.filled.hidden = !hasLines;
        parts.lines.replaceChildren(...totals.lines.map((line) => renderLine(parts.template, line)));
        renderTotals(totals);
    };

    if (pendingSku && products.has(pendingSku)) {
        writeLines(addLine(readLines(), pendingSku, pendingQuantity));
        announce(addedMessage(pendingQuantity, products.get(pendingSku).name));
    }

    render();

    parts.lines.addEventListener("change", (event) => {
        const row = event.target.closest("tr[data-sku]");

        if (!row || !event.target.matches("[data-line-quantity]")) {
            return;
        }

        const quantity = clampQuantity(event.target.value);
        const { sku } = row.dataset;

        writeLines(readLines().map((line) => (line.sku === sku ? { qty: quantity, sku } : line)));

        const totals = totalsFor();
        const line = totals.lines.find((entry) => entry.sku === sku);

        fillLine(row, line);
        renderTotals(totals);
        announce(`Quantity of ${line.product.name} updated to ${quantity}`);
    });

    parts.lines.addEventListener("click", (event) => {
        const button = event.target.closest("[data-line-remove]");

        if (!button) {
            return;
        }

        const { sku } = button.closest("tr[data-sku]").dataset;

        writeLines(readLines().filter((line) => line.sku !== sku));
        render();
        parts.title.focus();
        announce(`Removed ${products.get(sku).name} from your basket`);
    });
};

document.addEventListener("submit", handleAddToBasket);
document.addEventListener("submit", handleDemoForm);
document.addEventListener("submit", handleOrderForm);

if (document.querySelector("[data-basket-order-placed]")) {
    writeLines([]);
}

updateBadge(readLines());

const basketRoot = document.querySelector("[data-basket]");

if (basketRoot) {
    initialiseBasketPage(basketRoot);
}
