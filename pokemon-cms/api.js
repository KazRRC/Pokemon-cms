document.addEventListener("DOMContentLoaded", () => {
    const apiPokemonNames = new Set();
    const container = document.getElementById("pokemon-container");
    const searchInput = document.getElementById("search");

    apiPokemonNames.clear();

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
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const query = searchInput.value.toLowerCase().trim();

            if (query === "") {
                loadAllPokemon();
                return;
            }

            container.innerHTML = "";
            fetch("https://pokeapi.co/api/v2/pokemon?limit=1025")
                .then(res => res.json())
                .then(data => {
                    const filtered = data.results.filter(pokemon =>
                        pokemon.name.startsWith(query)
                    );

                    filtered.forEach(pokemon => {
                        displayAPIPokemon(pokemon.name);
                    });

                    if (filtered.length === 0) {
                    }
                });

            fetch(`search_db.php?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(pokemon => {
                            displayDBPokemon(pokemon);
                        });
                    } else {
                        setTimeout(() => {
                            if (container.innerHTML === "") {
                                container.innerHTML = "<p>No Pokémon found.</p>";
                            }
                        }, 300);
                    }
                });
        });
    }
});