document.addEventListener("DOMContentLoaded", () => {
    const apiPokemonNames = new Set();
    const container = document.getElementById("pokemon-container");
    const searchInput = document.getElementById("search");
    const categorySelect = document.getElementById("category");
    const RESULTS_PER_PAGE = 10;
    let currentPage = 1;
    let currentResults = [];
    apiPokemonNames.clear();

    function renderPage() {
    container.innerHTML = "";

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
        prev.textContent = "Previous";
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

    container.appendChild(pagination);
}

    function loadAllPokemon() {
        container.innerHTML = "";
        apiPokemonNames.clear();

        fetch("https://pokeapi.co/api/v2/pokemon?limit=50")
            .then(res => res.json())
            .then(data => {
                data.results.forEach(pokemon => {
                    displayAPIPokemon(pokemon.name);
                });
            });

        fetch("search_db.php?q=")
            .then(res => res.json())
            .then(data => {
                data.forEach(pokemon => {
                    displayDBPokemon(pokemon);
                });
            });
        }

    function filterByType(type) {
        container.innerHTML = "";

        fetch(`https://pokeapi.co/api/v2/type/${type}`)
            .then(res => res.json())
            .then(data => {
                data.pokemon.slice(0, 50).forEach(p => {
                    displayAPIPokemon(p.pokemon.name);
                });
            });
    }

    function displayAPIPokemon(name) {
        apiPokemonNames.add(name);
        fetch(`https://pokeapi.co/api/v2/pokemon/${name}`)
            .then(res => res.json())
            .then(data => {
                const card = document.createElement("div");
                card.classList.add("pokemon-card");

                card.innerHTML = `
                    <h3>${data.name}</h3>
                    <img src="${data.sprites.front_default}" />
                `;

                card.style.cursor = "pointer";

                card.addEventListener("click", () => {
                    window.location.href = `pokemon.php?name=${data.name}`;
                });

                container.appendChild(card);
            });
    }
    function displayDBPokemon(pokemon) {

        if (apiPokemonNames.has(pokemon.name.toLowerCase())) {
            return;
        }

        const card = document.createElement("div");
        card.classList.add("pokemon-card");

        card.innerHTML = `
            <h3>${pokemon.name}</h3>
            ${pokemon.image ? `<img src="uploads/${pokemon.image}" />` : ""}
        `;

        card.style.cursor = "pointer";

        card.addEventListener("click", () => {
            window.location.href = `pokemon.php?name=${pokemon.name}`;
        });

        container.appendChild(card);
    }

    window.loadAllPokemon = loadAllPokemon;
    window.filterByType = filterByType;

    loadAllPokemon();
function performSearch() {
    const query = searchInput.value.toLowerCase().trim();
    const category = categorySelect.value;

    if (query === "") {
        loadAllPokemon();
        return;
    }

    container.innerHTML = "";

    Promise.all([
        fetch("https://pokeapi.co/api/v2/pokemon?limit=1025")
            .then(res => res.json()),
        fetch(`search_db.php?q=${query}&category=${category}`)
            .then(res => res.json())
    ]).then(([apiData, dbData]) => {

        let apiFiltered = apiData.results.filter(pokemon =>
            pokemon.name.startsWith(query)
        );

        if (category !== "all") {
            apiFiltered = apiFiltered.filter(p => p.name.includes(category));
        }

        currentResults = [
            ...apiFiltered.map(p => ({ type: "api", data: p.name })),
            ...dbData.map(p => ({ type: "db", data: p }))
        ];

        currentPage = 1;
        renderPage();
    });
}

searchInput.addEventListener("input", performSearch);
categorySelect.addEventListener("change", performSearch);
});