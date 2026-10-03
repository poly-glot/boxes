const pounds = new Intl.NumberFormat("en-GB", { currency: "GBP", style: "currency" });
const normalise = (text) => text.toLocaleLowerCase("en-GB");

const toEntries = (index) => [
    ...index.products.map((product) => ({
        excerpt: product.summary,
        haystack: normalise(`${product.name} ${product.sku} ${product.categoryName} ${product.summary}`),
        image: product.image.small,
        meta: `SKU: ${product.sku} · ${product.pack} · ${pounds.format(product.price)} ex VAT`,
        title: product.name,
        type: "Product",
        url: product.url,
    })),
    ...index.categories.map((category) => ({
        excerpt: category.summary,
        haystack: normalise(`${category.name} ${category.summary}`),
        title: category.name,
        type: "Category",
        url: category.url,
    })),
    ...index.pages.map((page) => ({
        excerpt: page.summary,
        haystack: normalise(`${page.title} ${page.summary}`),
        title: page.title,
        type: page.kind,
        url: page.url,
    })),
];

export const findMatches = (entries, query) => {
    const terms = normalise(query).split(/\s+/).filter(Boolean);

    return entries.filter((entry) => terms.every((term) => entry.haystack.includes(term)));
};

const renderResult = (template, entry) => {
    const item = template.content.firstElementChild.cloneNode(true);
    const image = item.querySelector("[data-result-image]");
    const link = item.querySelector("[data-result-link]");
    const meta = item.querySelector("[data-result-meta]");

    item.querySelector("[data-result-type]").textContent = entry.type;
    item.querySelector("[data-result-excerpt]").textContent = entry.excerpt;
    link.href = entry.url;
    link.textContent = entry.title;
    meta.textContent = entry.meta ?? "";
    meta.hidden = !entry.meta;
    image.hidden = !entry.image;

    if (entry.image) {
        image.src = entry.image;
    }

    return item;
};

const describe = (count) => {
    if (count === 0) {
        return "Check the spelling, try a shorter word such as \u201Cmailer\u201D or \u201Ctape\u201D, or search by SKU, for example BX-SW305. You can also browse by category below.";
    }

    return `${count} ${count === 1 ? "result" : "results"}`;
};

const initialiseSearch = async (root) => {
    const summary = root.querySelector("[data-search-summary]");
    const fallback = root.querySelector("[data-search-fallback]");
    const list = root.querySelector("[data-search-list]");
    const template = root.querySelector("[data-search-result]");
    const heading = root.querySelector("#results-title");
    const input = document.getElementById("search-page-input");
    const query = (new URLSearchParams(window.location.search).get("s") ?? "").trim();

    fallback.hidden = true;
    fallback.querySelector("[data-search-nojs]").hidden = true;

    if (input) {
        input.value = query;
    }

    if (!query) {
        summary.textContent = "Enter a product name, a material or a SKU to search the shop.";
        return;
    }

    const response = await fetch("/assets/search-index.json").catch(() => null);

    if (!response?.ok) {
        summary.textContent = "Search is not available right now. Please browse by category instead.";
        fallback.hidden = false;
        return;
    }

    const matches = findMatches(toEntries(await response.json()), query);

    list.replaceChildren(...matches.map((entry) => renderResult(template, entry)));
    heading.textContent = `${matches.length === 0 ? "No results" : "Results"} for \u201C${query}\u201D`;
    summary.textContent = describe(matches.length);
    fallback.hidden = matches.length > 0;

    heading.focus();
};

const searchRoot = document.querySelector("[data-search]");

if (searchRoot) {
    initialiseSearch(searchRoot);
}
