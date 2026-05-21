<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto DSS 404 - API Tienda</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f6f7f9;
            --panel: #ffffff;
            --ink: #1e293b;
            --muted: #64748b;
            --line: #dbe3ec;
            --brand: #0f766e;
            --brand-dark: #0f5f59;
            --danger: #b91c1c;
            --soft: #e7f5f3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            background: #102a43;
            color: white;
            padding: 24px;
        }

        header div {
            max-width: 1180px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 14px;
        }

        h3 {
            font-size: 15px;
            margin-bottom: 8px;
        }

        main {
            max-width: 1180px;
            margin: 24px auto;
            padding: 0 16px 32px;
            display: grid;
            gap: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 16px;
            align-items: start;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 18px;
        }

        .muted {
            color: var(--muted);
            font-size: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin: 14px 0 6px;
        }

        input, select {
            width: 100%;
            min-height: 40px;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 9px 10px;
            font: inherit;
            color: var(--ink);
            background: white;
        }

        button {
            min-height: 40px;
            border: 0;
            border-radius: 6px;
            padding: 9px 13px;
            font: inherit;
            font-weight: 700;
            color: white;
            background: var(--brand);
            cursor: pointer;
        }

        button:hover {
            background: var(--brand-dark);
        }

        button.secondary {
            color: var(--ink);
            background: #e2e8f0;
        }

        button.secondary:hover {
            background: #cbd5e1;
        }

        button.danger {
            background: var(--danger);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .endpoint-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 32px;
            border-radius: 999px;
            padding: 6px 12px;
            color: #0f5132;
            background: #d1fae5;
            font-size: 13px;
            font-weight: 700;
        }

        .status.off {
            color: #7f1d1d;
            background: #fee2e2;
        }

        .result {
            min-height: 440px;
            overflow: auto;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #0f172a;
            color: #dbeafe;
            padding: 14px;
            font-family: Consolas, "Courier New", monospace;
            font-size: 13px;
            line-height: 1.45;
            white-space: pre-wrap;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .metric {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 14px;
            background: var(--soft);
        }

        .metric strong {
            display: block;
            font-size: 24px;
            margin-top: 6px;
        }

        @media (max-width: 900px) {
            header div, .grid {
                display: block;
            }

            header .status {
                margin-top: 14px;
            }

            .endpoint-grid, .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .panel + .panel {
                margin-top: 16px;
            }
        }

        @media (max-width: 560px) {
            .endpoint-grid, .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div>
            <section>
                <h1>Proyecto DSS 404 - API Tienda</h1>
                <p class="muted" style="color:#cbd5e1">Panel rapido para probar autenticacion y endpoints REST.</p>
            </section>
            <span id="sessionStatus" class="status off">Sin sesion</span>
        </div>
    </header>

    <main>
        <section class="cards">
            <article class="metric">
                <span class="muted">Version API</span>
                <strong>v1</strong>
            </article>
            <article class="metric">
                <span class="muted">Auth</span>
                <strong>Sanctum</strong>
            </article>
            <article class="metric">
                <span class="muted">CRUDs</span>
                <strong>5</strong>
            </article>
            <article class="metric">
                <span class="muted">Usuario seed</span>
                <strong>Admin</strong>
            </article>
        </section>

        <section class="grid">
            <aside class="panel">
                <h2>Acceso</h2>
                <p class="muted">Usa el usuario creado por los seeders.</p>

                <label for="email">Email</label>
                <input id="email" type="email" value="admin@tienda.com">

                <label for="password">Password</label>
                <input id="password" type="password" value="admin1234">

                <div class="actions">
                    <button type="button" onclick="login()">Login</button>
                    <button type="button" class="secondary" onclick="me()">Ver perfil</button>
                    <button type="button" class="danger" onclick="logout()">Logout</button>
                </div>
            </aside>

            <section class="panel">
                <h2>Endpoints</h2>
                <p class="muted">Despues del login puedes consultar las rutas protegidas.</p>
                <div class="endpoint-grid" style="margin-top:14px">
                    <button type="button" onclick="loadResource('usuarios')">Usuarios</button>
                    <button type="button" onclick="loadResource('categorias')">Categorias</button>
                    <button type="button" onclick="loadResource('productos')">Productos</button>
                    <button type="button" onclick="loadResource('pedidos')">Pedidos</button>
                    <button type="button" onclick="loadResource('items-pedido')">Items</button>
                </div>
            </section>
        </section>

        <section class="panel">
            <h2>Respuesta</h2>
            <pre id="output" class="result">Inicia sesion para probar la API.</pre>
        </section>
    </main>

    <script>
        const output = document.getElementById('output');
        const statusBadge = document.getElementById('sessionStatus');
        const tokenKey = 'dss404_api_token';

        function token() {
            return localStorage.getItem(tokenKey);
        }

        function setOutput(data) {
            output.textContent = typeof data === 'string'
                ? data
                : JSON.stringify(data, null, 2);
        }

        function updateStatus() {
            const hasToken = Boolean(token());
            statusBadge.textContent = hasToken ? 'Sesion activa' : 'Sin sesion';
            statusBadge.classList.toggle('off', !hasToken);
        }

        async function api(path, options = {}) {
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...(options.headers || {}),
            };

            if (token()) {
                headers.Authorization = `Bearer ${token()}`;
            }

            const response = await fetch(`/api/v1/${path}`, {
                ...options,
                headers,
            });

            const data = await response.json().catch(() => ({
                success: false,
                message: 'La respuesta no es JSON.',
            }));

            if (!response.ok) {
                throw data;
            }

            return data;
        }

        async function login() {
            try {
                const data = await api('auth/login', {
                    method: 'POST',
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                    }),
                });

                localStorage.setItem(tokenKey, data.data.token);
                updateStatus();
                setOutput(data);
            } catch (error) {
                setOutput(error);
            }
        }

        async function logout() {
            try {
                const data = await api('auth/logout', { method: 'POST' });
                localStorage.removeItem(tokenKey);
                updateStatus();
                setOutput(data);
            } catch (error) {
                localStorage.removeItem(tokenKey);
                updateStatus();
                setOutput(error);
            }
        }

        async function me() {
            try {
                setOutput(await api('auth/me'));
            } catch (error) {
                setOutput(error);
            }
        }

        async function loadResource(resource) {
            try {
                setOutput(await api(`${resource}?per_page=5`));
            } catch (error) {
                setOutput(error);
            }
        }

        updateStatus();
    </script>
</body>
</html>
