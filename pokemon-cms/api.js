document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("pokemon-container");

    function loadAllPokemon() {
        if (!container) {
            console.error("pokemon-container not found!");
            return;
        }

        container.innerHTML = "";

        fetch("https://pokeapi.co/api/v2/pokemon?limit=50")
            .then(res => res.json())
            .then(data => {
                data.results.forEach(pokemon => {
                    displayPokemon(pokemon.name);
                });
            });
    }

    function filterByType(type) {
        container.innerHTML = "";

        fetch(`https://pokeapi.co/api/v2/type/${type}`)
            .then(res => res.json())
            .then(data => {
                data.pokemon.slice(0, 50).forEach(p => {
                    displayPokemon(p.pokemon.name);
                });
            });
    }

    function displayPokemon(name) {
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

    window.loadAllPokemon = loadAllPokemon;
    window.filterByType = filterByType;

    loadAllPokemon();
});