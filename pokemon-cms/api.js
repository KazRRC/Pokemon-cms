document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("pokemon-container");
    const searchInput = document.getElementById("search");
    const categorySelect = document.getElementById("category");

    const RESULTS_PER_PAGE = 10;

    let currentResults = [];
    let currentPage = 1;
    let debounceTimer = null;

    container.innerHTML = "";
    function handleSearch() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const query = searchInput.value.toLowerCase().trim();
            const category = categorySelect ? categorySelect.value : "all";

            if (query === "") {
                container.innerHTML = "";
                currentResults = [];
                return;
            }

            fetchResults(query, category);
        }, 300);
    }

function getCategory() {
    return categorySelect ? categorySelect.value : "all";
}

function fetchResults(query, category) {
    currentResults = [];
    container.innerHTML = "<p>Loading...</p>";

    const apiRequest = fetch("https://pokeapi.co/api/v2/pokemon?limit=1025")
        .then(res => res.json())
        .catch(() => ({ results: [] }));

    const dbRequest = fetch(`search_db.php?q=${query}&category=${category}`)
        .then(res => res.json())
        .catch(() => []);

    Promise.all([apiRequest, dbRequest])
    .then(([apiData, dbData]) => {
        console.log("API:", apiData);
        console.log("DB:", dbData);
        let apiFiltered = apiData.results.filter(p =>
            p.name.startsWith(query)
        );

        const seen = new Set();
        currentResults = [];

        apiFiltered.forEach(p => {
            if (!seen.has(p.name)) {
                seen.add(p.name);
                currentResults.push({ type: "api", data: p.name });
            }
        });

        dbData.forEach(p => {
            if (!seen.has(p.name.toLowerCase())) {
                seen.add(p.name.toLowerCase());
                currentResults.push({ type: "db", data: p });
            }
        });

        currentPage = 1;
        renderPage();
        
    });
    console.log("Rendered page with:", currentResults.length, "results");
}

function renderPage() {
    container.innerHTML = "";
    if (currentResults.length === 0) {
        container.innerHTML = "<p>No Pokémon found.</p>";
        return;
    }

    const start = (currentPage - 1) * RESULTS_PER_PAGE;
    const end = start + RESULTS_PER_PAGE;

    const pageResults = currentResults.slice(start, end);

    pageResults.forEach(item => {
        if (item.type === "api") {
            displayAPIPokemon(item.data);
        } else {
            displayDBPokemon(item.data);
        }
    });

    renderPagination();
}

    function renderPagination() {
        const totalPages = Math.ceil(currentResults.length / RESULTS_PER_PAGE);
        if (totalPages <= 1) return;

        const pagination = document.createElement("div");
        pagination.classList.add("pagination");

        if (currentPage > 1) {
            const prev = document.createElement("button");
            prev.textContent = "Prev";
            prev.onclick = () => {
                currentPage--;
                renderPage();
            };
            pagination.appendChild(prev);
        }

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;

            if (i === currentPage) {
                btn.disabled = true;
            }

            btn.onclick = () => {
                currentPage = i;
                renderPage();
            };

            pagination.appendChild(btn);
        }

        if (currentPage < totalPages) {
            const next = document.createElement("button");
            next.textContent = "Next";
            next.onclick = () => {
                currentPage++;
                renderPage();
            };
            pagination.appendChild(next);
        }

        let paginationContainer = document.getElementById("pagination");

    if (!paginationContainer) {
        paginationContainer = document.createElement("div");
        paginationContainer.id = "pagination";
        container.parentNode.appendChild(paginationContainer);
    }

    paginationContainer.innerHTML = "";
    paginationContainer.appendChild(pagination);
    }

function displayAPIPokemon(name) {
    const card = document.createElement("div");
    card.classList.add("pokemon-card");

    card.innerHTML = `<h3>${name}</h3><p>Loading...</p>`;
    container.appendChild(card);

    fetch(`https://pokeapi.co/api/v2/pokemon/${name}`)
        .then(res => res.json())
        .then(data => {
            card.innerHTML = `
                <h3>${data.name}</h3>
                <img src="${data.sprites.front_default}" />
            `;

            card.onclick = () => {
                window.location.href = `pokemon.php?type=api&name=${data.name}`;
            };
        });
}

    function displayDBPokemon(pokemon) {
        const card = document.createElement("div");
        card.classList.add("pokemon-card");

        card.innerHTML = `
            <a href="${link}" style="text-decoration:none; color:black;">
                <img src="${p.image || ''}">
                <strong>${p.name}</strong>
                <p>HP: ${p.hp ?? '-'}</p>

                <span class="type-badge type-${p.type}">
                    ${p.type ?? 'unknown'}
                </span>
            </a>
        `;

        card.onclick = () => {
            window.location.href = `pokemon.php?type=db&name=${pokemon.name}`;
        };

        container.appendChild(card);
    }
    searchInput.addEventListener("input", handleSearch);

    if (categorySelect) {
        categorySelect.addEventListener("change", handleSearch);
    }
});