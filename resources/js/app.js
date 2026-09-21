import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const app = document.getElementById('app');
let token = localStorage.getItem('token');

function telaLogin() {
    app.innerHTML = `
        <div class="spa-page">
            <div class="spa-card login-card">
                <h1>Banco</h1>
                <p class="muted">Acesse sua conta</p>
                <form id="loginForm">
                    <input id="loginEmail" type="email" placeholder="Email" required>
                    <input id="loginPassword" type="password" placeholder="Senha" required>
                    <input id="deviceName" type="text" placeholder="Dispositivo" value="SPA" required>
                    <button type="submit">Entrar</button>
                </form>
                <p id="loginMessage" class="message"></p>
            </div>
        </div>
    `;

    document.getElementById('loginForm').addEventListener('submit', login);
}

async function login(event) {
    event.preventDefault();

    const response = await fetch('/api/login', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
            email: document.getElementById('loginEmail').value,
            password: document.getElementById('loginPassword').value,
            device_name: document.getElementById('deviceName').value
        })
    });

    const data = await response.json();

    if (!response.ok) {
        document.getElementById('loginMessage').textContent = data.message || 'Login invalido.';
        return;
    }

    token = data.token;
    localStorage.setItem('token', token);
    carregarDashboard();
}

async function api(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            ...(options.headers || {})
        }
    });

    if (response.status === 401) {
        localStorage.removeItem('token');
        token = null;
        telaLogin();
        return null;
    }

    return response;
}

async function carregarDashboard() {
    const response = await api('/api/saldo');

    if (!response) return;

    const saldo = await response.json();

    app.innerHTML = `
        <div class="spa-page">
            <div class="spa-header">
                <div>
                    <h1>Minha conta</h1>
                    <p class="muted">SPA + API RESTful</p>
                </div>
                <button id="logoutButton" class="secondary">Sair</button>
            </div>

            <div class="cards">
                <div class="spa-card">
                    <span>Saldo</span>
                    <strong>R$ ${Number(saldo.saldo).toFixed(2)}</strong>
                </div>
                <div class="spa-card">
                    <span>Limite</span>
                    <strong>R$ ${Number(saldo.limite).toFixed(2)}</strong>
                </div>
                <div class="spa-card">
                    <span>Status</span>
                    <strong>${saldo.status}</strong>
                </div>
            </div>

            <div class="spa-grid">
                <div class="spa-card">
                    <h2>Pix</h2>
                    <form id="pixForm">
                        <input id="pixEmail" type="email" placeholder="Email do destinatario" required>
                        <input id="pixValor" type="number" step="0.01" min="0.01" placeholder="Valor" required>
                        <input id="pixDescricao" type="text" placeholder="Descricao">
                        <button type="submit">Enviar Pix</button>
                    </form>
                    <p id="pixMessage" class="message"></p>
                </div>

                <div class="spa-card">
                    <h2>Extrato</h2>
                    <button id="extratoButton">Atualizar extrato</button>
                    <div id="extrato"></div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('logoutButton').addEventListener('click', logout);
    document.getElementById('pixForm').addEventListener('submit', enviarPix);
    document.getElementById('extratoButton').addEventListener('click', carregarExtrato);

    carregarExtrato();
}

async function enviarPix(event) {
    event.preventDefault();

    const response = await api('/api/pix', {
        method: 'POST',
        body: JSON.stringify({
            email: document.getElementById('pixEmail').value,
            valor: Number(document.getElementById('pixValor').value),
            descricao: document.getElementById('pixDescricao').value
        })
    });

    if (!response) return;

    const data = await response.json();
    document.getElementById('pixMessage').textContent = data.message || 'Pix realizado.';

    if (response.ok) {
        document.getElementById('pixForm').reset();
        carregarDashboard();
    }
}

async function carregarExtrato() {
    const response = await api('/api/extrato');

    if (!response) return;

    const data = await response.json();
    const extrato = document.getElementById('extrato');

    if (!Array.isArray(data) || data.length === 0) {
        extrato.innerHTML = '<p class="muted">Nenhuma movimentacao.</p>';
        return;
    }

    extrato.innerHTML = data.map(item => `
        <div class="movimentacao">
            <div>
                <strong>${item.tipo}</strong>
                <small>${item.descricao || ''}</small>
            </div>
            <span>${item.natureza === 'entrada' ? '+' : '-'} R$ ${Number(item.valor).toFixed(2)}</span>
        </div>
    `).join('');
}

async function logout() {
    await api('/api/logout', {method: 'POST'});
    localStorage.removeItem('token');
    token = null;
    telaLogin();
}

if (token) carregarDashboard();
else telaLogin();
