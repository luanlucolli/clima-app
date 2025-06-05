/**
 * autocomplete.js
 * 
 * Lógica de autocomplete para municípios brasileiros (IBGE).
 * - Ao digitar no #cidade_autocomplete, faz fetch para /municipios?q=…
 * - Exibe até 10 sugestões abaixo do input.
 * - Ao clicar em uma sugestão, preenche campos ocultos e o próprio input.
 * - Na tentativa de submit sem seleção, bloqueia e exibe um toast de erro.
 */

import { showToast } from './toasts'; // Importar função para exibir toast

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cidade_autocomplete');
    const listContainer = document.getElementById('autocomplete-list');
    const hiddenCidade = document.getElementById('cidade_selected');
    const hiddenUF = document.getElementById('uf_selected');
    const form = document.getElementById('form-clima');

    let debounceTimer = null;

    // Função para limpar sugestões
    function clearSuggestions() {
        listContainer.innerHTML = '';
    }

    // Renderiza lista de municípios (até 10 itens)
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
                showToast('error', 'Falha ao buscar sugestões de municípios.');
            });
    }

    // Evento de input com debounce de 300ms
    input.addEventListener('input', function () {
        const query = this.value.trim();

        // Limpar campos ocultos ao digitar (para forçar nova seleção)
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

    // Interceptar submit: se não houver seleção válida, impedir e exibir toast
    form.addEventListener('submit', function (e) {
        if (!hiddenCidade.value || !hiddenUF.value) {
            e.preventDefault();
            showToast('error', 'Por favor, selecione uma cidade válida na lista de sugestões.');
            input.value = '';
            input.focus();
            hiddenCidade.value = '';
            hiddenUF.value = '';
            clearSuggestions();
        }
    });
});
