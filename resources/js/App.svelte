<script>
    import { onMount } from 'svelte';

    const TOKEN_KEY = 'banco_api_token';
    const USER_KEY = 'banco_api_user';

    let token = localStorage.getItem(TOKEN_KEY);
    let user = JSON.parse(localStorage.getItem(USER_KEY) || 'null');

    let loading = false;
    let loadingData = false;
    let error = '';
    let success = '';
    let activeTab = 'inicio';

    let email = '';
    let password = '';

    let saldo = {
        saldo: 0,
        limite: 0,
        status: '—',
        saldo_cdb: 0,
        saldo_cdi: 0,
        saldo_poupanca: 0,
    };

    let extrato = [];

    let pix = { email: '', valor: '', descricao: '' };
    let investimento = { tipo: 'cdb', valor: '' };
    let resgate = { tipo: 'cdb', valor: '' };

    const money = (value) =>
        Number(value || 0).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        });

    const date = (value) =>
        value
            ? new Date(value).toLocaleString('pt-BR', {
                  dateStyle: 'short',
                  timeStyle: 'short',
              })
            : '—';

    async function api(path, options = {}) {
        const headers = {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers || {}),
        };

        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }

        const response = await fetch(path, {
            ...options,
            headers,
        });

        let data = null;
        try {
            data = await response.json();
        } catch (_) {
            data = null;
        }

        if (response.status === 401) {
            logout(false);
            throw new Error('Sessão expirada. Faça login novamente.');
        }

        if (!response.ok) {
            const validation = data?.errors
                ? Object.values(data.errors).flat().join(' ')
                : '';
            throw new Error(
                validation || data?.message || 'Não foi possível concluir a operação.'
            );
        }

        return data;
    }

    async function login() {
        error = '';
        success = '';
        loading = true;

        try {
            const data = await api('/api/login', {
                method: 'POST',
                body: JSON.stringify({
                    email,
                    password,
                    device_name: 'svelte-spa',
                }),
            });

            token = data.token;
            user = data.user;

            localStorage.setItem(TOKEN_KEY, token);
            localStorage.setItem(USER_KEY, JSON.stringify(user));

            await loadData();
            activeTab = 'inicio';
        } catch (e) {
            error = e.message;
        } finally {
            loading = false;
        }
    }

    async function loadData() {
        if (!token) return;

        loadingData = true;
        error = '';

        try {
            const [me, account, statement] = await Promise.all([
                api('/api/user'),
                api('/api/saldo'),
                api('/api/extrato'),
            ]);

            user = me;
            saldo = account;
            extrato = Array.isArray(statement) ? statement : statement?.data || [];

            localStorage.setItem(USER_KEY, JSON.stringify(user));
        } catch (e) {
            error = e.message;
        } finally {
            loadingData = false;
        }
    }

    async function submitPix() {
        error = '';
        success = '';

        try {
            await api('/api/pix', {
                method: 'POST',
                body: JSON.stringify({
                    email: pix.email,
                    valor: Number(pix.valor),
                    descricao: pix.descricao,
                }),
            });

            pix = { email: '', valor: '', descricao: '' };
            success = 'PIX realizado com sucesso.';
            await loadData();
            activeTab = 'extrato';
        } catch (e) {
            error = e.message;
        }
    }

    async function submitApplication() {
        error = '';
        success = '';

        try {
            await api('/api/aplicar', {
                method: 'POST',
                body: JSON.stringify({
                    tipo: investimento.tipo,
                    valor: Number(investimento.valor),
                }),
            });

            investimento.valor = '';
            success = 'Aplicação realizada com sucesso.';
            await loadData();
            activeTab = 'investimentos';
        } catch (e) {
            error = e.message;
        }
    }

    async function submitRedemption() {
        error = '';
        success = '';

        try {
            await api('/api/resgatar', {
                method: 'POST',
                body: JSON.stringify({
                    tipo: resgate.tipo,
                    valor: Number(resgate.valor),
                }),
            });

            resgate.valor = '';
            success = 'Resgate realizado com sucesso.';
            await loadData();
            activeTab = 'investimentos';
        } catch (e) {
            error = e.message;
        }
    }

    async function logout(callApi = true) {
        try {
            if (callApi && token) {
                await api('/api/logout', { method: 'POST' });
            }
        } catch (_) {
            // Mesmo que a API falhe, a sessão local deve ser encerrada.
        }

        token = null;
        user = null;
        extrato = [];
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
        activeTab = 'inicio';
        error = '';
        success = '';
    }

    onMount(async () => {
        if (token) {
            await loadData();
        }
    });
</script>

{#if !token}
    <main class="auth-shell">
        <section class="auth-card">
            <div class="brand">
                <div class="brand-mark">B</div>
                <div>
                    <strong>Banco SPA</strong>
                    <span>Laravel API + Svelte</span>
                </div>
            </div>

            <h1>Acessar conta</h1>
            <p class="muted">Entre para consultar saldo e movimentar sua conta.</p>

            {#if error}
                <div class="alert error">{error}</div>
            {/if}

            <form on:submit|preventDefault={login}>
                <label>
                    E-mail
                    <input bind:value={email} type="email" autocomplete="email" required placeholder="cliente@email.com">
                </label>

                <label>
                    Senha
                    <input bind:value={password} type="password" autocomplete="current-password" required placeholder="••••••••">
                </label>

                <button class="primary full" disabled={loading}>
                    {loading ? 'Entrando...' : 'Entrar'}
                </button>
            </form>

            <small class="hint">A autenticação usa o endpoint REST <code>POST /api/login</code>.</small>
        </section>
    </main>
{:else}
    <div class="app-shell">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark">B</div>
                <div>
                    <strong>Banco SPA</strong>
                    <span>Conta digital</span>
                </div>
            </div>

            <div class="top-actions">
                <span class="user-name">{user?.name || 'Cliente'}</span>
                <button class="ghost" on:click={() => logout()}>Sair</button>
            </div>
        </header>

        <div class="layout">
            <aside class="sidebar">
                <button class:active={activeTab === 'inicio'} on:click={() => activeTab = 'inicio'}>Início</button>
                <button class:active={activeTab === 'pix'} on:click={() => activeTab = 'pix'}>PIX</button>
                <button class:active={activeTab === 'extrato'} on:click={() => activeTab = 'extrato'}>Extrato</button>
                <button class:active={activeTab === 'investimentos'} on:click={() => activeTab = 'investimentos'}>Investimentos</button>
            </aside>

            <main class="content">
                {#if error}
                    <div class="alert error">{error}</div>
                {/if}

                {#if success}
                    <div class="alert success">{success}</div>
                {/if}

                {#if activeTab === 'inicio'}
                    <section class="page-heading">
                        <div>
                            <p class="eyebrow">Visão geral</p>
                            <h1>Olá, {user?.name?.split(' ')[0] || 'cliente'}.</h1>
                            <p class="muted">Aqui está um resumo da sua conta.</p>
                        </div>
                        <button class="ghost" on:click={loadData} disabled={loadingData}>
                            {loadingData ? 'Atualizando...' : 'Atualizar'}
                        </button>
                    </section>

                    <div class="cards">
                        <article class="card balance">
                            <span>Saldo disponível</span>
                            <strong>{money(saldo.saldo)}</strong>
                            <small>Limite: {money(saldo.limite)}</small>
                        </article>

                        <article class="card">
                            <span>Status da conta</span>
                            <strong class:blocked={saldo.status === 'bloqueada'}>{saldo.status}</strong>
                            <small>Conta do cliente autenticado</small>
                        </article>

                        <article class="card">
                            <span>Investimentos</span>
                            <strong>{money(Number(saldo.saldo_cdb) + Number(saldo.saldo_cdi) + Number(saldo.saldo_poupanca))}</strong>
                            <small>CDB + CDI + Poupança</small>
                        </article>
                    </div>

                    <section class="panel">
                        <div class="panel-title">
                            <h2>Últimas movimentações</h2>
                            <button class="link" on:click={() => activeTab = 'extrato'}>Ver extrato</button>
                        </div>

                        {#if extrato.length}
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr><th>Data</th><th>Tipo</th><th>Descrição</th><th>Valor</th></tr>
                                    </thead>
                                    <tbody>
                                        {#each extrato.slice(0, 5) as item}
                                            <tr>
                                                <td>{date(item.created_at)}</td>
                                                <td>{item.tipo}</td>
                                                <td>{item.descricao}</td>
                                                <td class:income={item.natureza === 'entrada'} class="money">
                                                    {item.natureza === 'entrada' ? '+' : '-'} {money(item.valor)}
                                                </td>
                                            </tr>
                                        {/each}
                                    </tbody>
                                </table>
                            </div>
                        {:else}
                            <div class="empty">Nenhuma movimentação encontrada.</div>
                        {/if}
                    </section>
                {:else if activeTab === 'pix'}
                    <section class="page-heading">
                        <div>
                            <p class="eyebrow">Transferência</p>
                            <h1>PIX por e-mail</h1>
                            <p class="muted">Envie dinheiro para outro cliente cadastrado usando o e-mail da conta.</p>
                        </div>
                    </section>

                    <section class="panel form-panel">
                        <form on:submit|preventDefault={submitPix}>
                            <label>
                                E-mail do destinatário
                                <input bind:value={pix.email} type="email" required placeholder="destinatario@email.com">
                            </label>

                            <label>
                                Valor
                                <input bind:value={pix.valor} type="number" min="0.01" step="0.01" required placeholder="0,00">
                            </label>

                            <label>
                                Descrição
                                <input bind:value={pix.descricao} maxlength="150" placeholder="Ex.: Pagamento">
                            </label>

                            <div class="form-footer">
                                <span>Saldo atual: <strong>{money(saldo.saldo)}</strong></span>
                                <button class="primary" disabled={saldo.status === 'bloqueada'}>Enviar PIX</button>
                            </div>
                        </form>
                    </section>
                {:else if activeTab === 'extrato'}
                    <section class="page-heading">
                        <div>
                            <p class="eyebrow">Movimentações</p>
                            <h1>Extrato</h1>
                        </div>
                        <button class="ghost" on:click={loadData} disabled={loadingData}>
                            {loadingData ? 'Atualizando...' : 'Atualizar'}
                        </button>
                    </section>

                    <section class="panel">
                        {#if extrato.length}
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr><th>Data</th><th>Tipo</th><th>Descrição</th><th>Natureza</th><th>Valor</th></tr>
                                    </thead>
                                    <tbody>
                                        {#each extrato as item}
                                            <tr>
                                                <td>{date(item.created_at)}</td>
                                                <td>{item.tipo}</td>
                                                <td>{item.descricao}</td>
                                                <td>{item.natureza}</td>
                                                <td class:income={item.natureza === 'entrada'} class="money">
                                                    {item.natureza === 'entrada' ? '+' : '-'} {money(item.valor)}
                                                </td>
                                            </tr>
                                        {/each}
                                    </tbody>
                                </table>
                            </div>
                        {:else}
                            <div class="empty">Nenhuma movimentação encontrada.</div>
                        {/if}
                    </section>
                {:else if activeTab === 'investimentos'}
                    <section class="page-heading">
                        <div>
                            <p class="eyebrow">Patrimônio</p>
                            <h1>Investimentos</h1>
                            <p class="muted">Aplique parte do saldo e faça resgates quando necessário.</p>
                        </div>
                    </section>

                    <div class="investment-grid">
                        <article class="card"><span>CDB</span><strong>{money(saldo.saldo_cdb)}</strong></article>
                        <article class="card"><span>CDI</span><strong>{money(saldo.saldo_cdi)}</strong></article>
                        <article class="card"><span>Poupança</span><strong>{money(saldo.saldo_poupanca)}</strong></article>
                    </div>

                    <div class="two-col">
                        <section class="panel form-panel">
                            <h2>Aplicar</h2>
                            <form on:submit|preventDefault={submitApplication}>
                                <label>
                                    Produto
                                    <select bind:value={investimento.tipo}>
                                        <option value="cdb">CDB</option>
                                        <option value="cdi">CDI</option>
                                        <option value="poupanca">Poupança</option>
                                    </select>
                                </label>
                                <label>
                                    Valor
                                    <input bind:value={investimento.valor} type="number" min="0.01" step="0.01" required>
                                </label>
                                <button class="primary" disabled={saldo.status === 'bloqueada'}>Aplicar</button>
                            </form>
                        </section>

                        <section class="panel form-panel">
                            <h2>Resgatar</h2>
                            <form on:submit|preventDefault={submitRedemption}>
                                <label>
                                    Produto
                                    <select bind:value={resgate.tipo}>
                                        <option value="cdb">CDB</option>
                                        <option value="cdi">CDI</option>
                                        <option value="poupanca">Poupança</option>
                                    </select>
                                </label>
                                <label>
                                    Valor
                                    <input bind:value={resgate.valor} type="number" min="0.01" step="0.01" required>
                                </label>
                                <button class="primary" disabled={saldo.status === 'bloqueada'}>Resgatar</button>
                            </form>
                        </section>
                    </div>
                {/if}
            </main>
        </div>
    </div>
{/if}
