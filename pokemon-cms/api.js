document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("pokemon-container");
    const searchInput = document.getElementById("search");
    const typeSelect = document.querySelector("select[name='type']");

    const RESULTS_PER_PAGE = 10;

    let currentResults = [];
    let currentPage = 1;
    let debounceTimer = null;

    function handleSearch() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const query = searchInput.value.toLowerCase().trim();
            const type = typeSelect ? typeSelect.value : "all";

            fetchResults(query, type);
        }, 300);
    }

    function fetchResults(query, type) {
        container.innerHTML = "<p>Loading...</p>";
        currentResults = [];

        const apiRequest = fetch("https://pokeapi.co/api/v2/pokemon?limit=151")
            .then(res => res.json())
            .catch(() => ({ results: [] }));

        const dbRequest = fetch(`search_db.php?q=${query}&type=${type}`)
            .then(res => res.json())
            .catch(() => []);

        Promise.all([apiRequest, dbRequest]).then(([apiData, dbData]) => {
            const seen = new Set();
            const apiPromises = apiData.results.map(p =>
                fetch(p.url)
                    .then(res => res.json())
                    .then(data => {
                        const name = data.name;
                        const pokeType = data.types[0].type.name;
                        const matchesName = name.startsWith(query);
                        const matchesType = type === "all" || pokeType === type;

                        if (matchesName && matchesType && !seen.has(name)) {
                            seen.add(name);

                            return {
                                source: "api",
                                name: name,
                                image: data.sprites.front_default,
                                hp: data.stats[0].base_stat,
                                type: pokeType
                            };
                        }
                    })
            );

            Promise.all(apiPromises).then(apiResults => {

                apiResults.filter(Boolean).forEach(p => currentResults.push(p));
                dbData.forEach(p => {
                    const name = p.name.toLowerCase();
                    const pokeType = (p.type || "").toLowerCase();

                    const matchesName = name.startsWith(query);
                    const matchesType = type === "all" || pokeType === type;

                    if (matchesName && matchesType && !seen.has(name)) {
                        seen.add(name);

                        currentResults.push({
                            source: "db",
                            id: p.pokemon_id,
                            name: p.name,
                            image: p.image ? "uploads/" + p.image : null,
                            hp: p.hitpoints,
                            type: p.type
                        });
                    }
                });
                currentResults.sort((a, b) =>
                    a.name.localeCompare(b.name)
                );

                currentPage = 1;
                renderPage();
            });
        });
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

        pageResults.forEach(p => displayPokemon(p));

        renderPagination();
    }

    function displayPokemon(p) {
        const card = document.createElement("div");
        card.classList.add("pokemon-card");

        const link = (p.source === "api")
            ? `pokemon.php?name=${p.name}`
            : `pokemon.php?id=${p.id}`;

        card.innerHTML = `
            <a href="${link}" style="text-decoration:none; color:black;">
                <img src="${p.image || ''}">
                <strong>${p.name}</strong>
                <p>HP: ${p.hp ?? '-'}</p>
                <span class="type ${p.type}">${p.type ?? 'unknown'}</span>
            </a>
        `;

        container.appendChild(card);
    }

    function renderPagination() {
        let paginationContainer = document.getElementById("pagination");

        if (!paginationContainer) {
            paginationContainer = document.createElement("div");
            paginationContainer.id = "pagination";
            container.parentNode.appendChild(paginationContainer);
        }

        paginationContainer.innerHTML = "";

        const totalPages = Math.ceil(currentResults.length / RESULTS_PER_PAGE);
        if (totalPages <= 1) return;

        const pagination = document.createElement("div");

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

            if (i === currentPage) btn.disabled = true;

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

        paginationContainer.appendChild(pagination);
    }

    searchInput.addEventListener("input", handleSearch);

    if (typeSelect) {
        typeSelect.addEventListener("change", handleSearch);
    }
    fetchResults("", typeSelect ? typeSelect.value : "all");
});