/**
 * autocomplete.js
 * 
 * Lógica de autocomplete para municípios brasileiros (IBGE).
 * - Ao digitar no #cidade_autocomplete, faz fetch para /municipios?q=…
 * - Exibe até 10 sugestões abaixo do input.
 * - Ao clicar em uma sugestão, preenche campos ocultos e o próprio input.
 */

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cidade_autocomplete');
    const listContainer = document.getElementById('autocomplete-list');
    const hiddenCidade = document.getElementById('cidade_selected');
    const hiddenUF = document.getElementById('uf_selected');

    let debounceTimer = null;

    // Limpa a lista de sugestões
    function clearSuggestions() {
        listContainer.innerHTML = '';
    }

    // Renderiza array de municípios (até 10 itens)
    function renderSuggestions(items) {
        clearSuggestions();

        if (!items.length) {
            return;
        }

        items.forEach(item => {
            const nomeCompleto = `${item.nome}, ${item.uf_nome}`;
            const displayText = `${item.nome} — ${item.uf_sigla}`;

            const a = document.createElement('a');
            a.classList.add('list-group-item', 'list-group-item-action');
            a.textContent = displayText;
            a.href = 'javascript:void(0)';

            a.addEventListener('click', function () {
                // Preenche o input visível e os campos ocultos
                input.value = nomeCompleto;
                hiddenCidade.value = item.nome;
                hiddenUF.value = item.uf_nome;
                clearSuggestions();
            });

            listContainer.appendChild(a);
        });
    }

    // Busca municípios via fetch e chama renderSuggestions
    function fetchSuggestions(query) {
        fetch(`/municipios?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                renderSuggestions(data);
            })
            .catch(err => {
                console.error('Erro ao buscar municípios:', err);
            });
    }

    // Evento de input com debounce de 300ms
    input.addEventListener('input', function () {
        const query = this.value.trim();

        hiddenCidade.value = '';
        hiddenUF.value = '';

        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        if (query.length < 2) {
            clearSuggestions();
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });

    // Fecha lista de sugestões ao clicar fora
    document.addEventListener('click', function (e) {
        if (e.target !== input) {
            clearSuggestions();
        }
    });
});
