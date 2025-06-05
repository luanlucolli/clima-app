/**
 * resources/js/toasts.js
 * 
 * Exporta a função showToast(type, message) para criar e exibir
 * um toast dinamicamente no container definido no layout.
 * 
 * Observe que agora importamos o Bootstrap como módulo, para que
 * possamos usar `bootstrap.Toast` dentro deste escopo.
 */

import * as bootstrap from 'bootstrap';

export function showToast(type, message) {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;

    let title = '';
    let headerClass = '';

    if (type === 'success') {
        title = 'Sucesso';
        headerClass = 'bg-success text-white';
    } else if (type === 'error') {
        title = 'Erro';
        headerClass = 'bg-danger text-white';
    } else {
        title = 'Aviso';
        headerClass = 'bg-warning text-dark';
    }

    // Cria o elemento <div class="toast"> ... </div>
    const toastEl = document.createElement('div');
    toastEl.classList.add('toast', 'align-items-center', 'text-white', 'border-0');
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.setAttribute('data-bs-delay', '3000');

    toastEl.innerHTML = `
        <div class="toast-header ${headerClass}">
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>
        <div class="toast-body bg-white text-dark">
            ${message}
        </div>
    `;

    toastContainer.appendChild(toastEl);

    // Note que agora usamos o bootstrap importado ali em cima:
    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();

    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}
