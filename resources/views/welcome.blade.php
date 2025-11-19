<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="indigo">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inventario IT</title>

    {{-- Icons/manifest --}}
    <link rel="icon" href="{{ asset('img/it/favicon-32.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ asset('img/it/favicon-16.png') }}" sizes="16x16" />
    <link rel="apple-touch-icon" href="{{ asset('img/it/apple-touch-icon.png') }}" />

    {{-- Fuente --}}
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
      /* =========================
         Tokens base (no identitarios)
      ==========================*/
      :root{
        --br:16px;
        --ring:rgba(255,255,255,.08);
        --muted:#a1a1aa;
      }

      /* =========================
         Tema: INDIGO (actual)
      ==========================*/
      :root,
      [data-theme="indigo"]{
        --bg:#0b0c10; --panel:#0f1115; --text:#e5e7eb;
        --primary:#3b82f6; --primary-600:#2563eb;
        --card:#0f1218; --card-2:#0c0f14; --edge:#1a1f2a;
        --ok:#22c55e; --warn:#f59e0b; --danger:#ef4444;
        --grad1:#0b1020; --grad2:#0a0a0a; --glow:rgba(59,130,246,.14);
        --accentA: rgba(99,102,241,.12);
        --accentB: rgba(236,72,153,.10);
        --stroke: rgba(255,255,255,.03);
        --dash: var(--edge);
      }

      /* =========================
         Tema: EMERALD (verde)
      ==========================*/
      [data-theme="emerald"]{
        --bg:#07120e; --panel:#0b1612; --text:#e6f6ef;
        --primary:#10b981; --primary-600:#059669;
        --card:#0c1713; --card-2:#0a1410; --edge:#12211c;
        --ok:#10b981; --warn:#f59e0b; --danger:#ef4444;
        --grad1:#071a14; --grad2:#050f0c; --glow:rgba(16,185,129,.16);
        --accentA: rgba(16,185,129,.16);
        --accentB: rgba(94,234,212,.10);
        --stroke: rgba(255,255,255,.04);
        --dash:#193127;
      }

      /* =========================
         Tema: ROSE (magenta/rose)
      ==========================*/
      [data-theme="rose"]{
        --bg:#120811; --panel:#160b13; --text:#fde8f1;
        --primary:#f43f5e; --primary-600:#e11d48;
        --card:#190e17; --card-2:#140a12; --edge:#2a1420;
        --ok:#22c55e; --warn:#f59e0b; --danger:#fb7185;
        --grad1:#200916; --grad2:#0b070a; --glow:rgba(244,63,94,.18);
        --accentA: rgba(244,63,94,.18);
        --accentB: rgba(168,85,247,.12);
        --stroke: rgba(255,255,255,.05);
        --dash:#3a1a2a;
      }

      /* =========================
         Tema: AMBER (cálido)
      ==========================*/
      [data-theme="amber"]{
        --bg:#120e03; --panel:#181202; --text:#fff7e6;
        --primary:#f59e0b; --primary-600:#d97706;
        --card:#1b1404; --card-2:#151004; --edge:#2a200a;
        --ok:#22c55e; --warn:#f59e0b; --danger:#ef4444;
        --grad1:#1a1104; --grad2:#0b0803; --glow:rgba(245,158,11,.18);
        --accentA: rgba(245,158,11,.18);
        --accentB: rgba(251,113,133,.12);
        --stroke: rgba(255,255,255,.05);
        --dash:#3a2d0e;
      }

      /* =========================
         Tema: GRAPHITE (oscuro neutro)
      ==========================*/
      [data-theme="graphite"]{
        --bg:#0b0b0c; --panel:#0f1012; --text:#e6e7ea;
        --primary:#818cf8; --primary-600:#6366f1;
        --card:#121316; --card-2:#0f1013; --edge:#1c1e23;
        --ok:#22c55e; --warn:#eab308; --danger:#ef4444;
        --grad1:#0d0e12; --grad2:#090a0d; --glow:rgba(129,140,248,.12);
        --accentA: rgba(129,140,248,.12);
        --accentB: rgba(148,163,184,.10);
        --stroke: rgba(255,255,255,.04);
        --dash:#21232a;
      }

      /* =========================
         Tema: LIGHT (claro)
         Mantiene primario azul para identidad
      ==========================*/
      [data-theme="light"]{
        --bg:#f6f8fb; --panel:#ffffff; --text:#0f172a;
        --primary:#3b82f6; --primary-600:#2563eb;
        --card:#ffffff; --card-2:#f8fafc; --edge:#e5e7eb;
        --ok:#16a34a; --warn:#d97706; --danger:#dc2626;
        --grad1:#ffffff; --grad2:#f6f7fb; --glow:rgba(59,130,246,.10);
        --accentA: rgba(59,130,246,.10);
        --accentB: rgba(236,72,153,.08);
        --ring:rgba(15,23,42,.10);
        --muted:#475569;
        --stroke: rgba(15,23,42,.06);
        --dash:#e2e8f0;
      }

      *{box-sizing:border-box}
      html,body{height:100%}
      body{
        margin:0; font-family:"Instrument Sans",ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial;
        color:var(--text);
        background:
          radial-gradient(900px 500px at 20% -10%, var(--accentA), transparent 60%),
          radial-gradient(900px 500px at 120% 10%, var(--accentB), transparent 60%),
          linear-gradient(180deg,var(--grad1),var(--grad2)) fixed;
      }
      .container{max-width:1180px;margin-inline:auto;padding:28px}
      .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
      .brand{display:flex;align-items:center;gap:12px}
      .logo{width:28px;height:28px;border-radius:999px;display:inline-block;box-shadow:0 0 0 4px var(--stroke)}
      .title{font-weight:700;letter-spacing:.2px}
      .tag{font-size:11px;padding:2px 8px;border-radius:999px;border:1px solid var(--ring);color:#94a3b8;background:rgba(255,255,255,.02)}
      nav{display:flex;gap:10px;align-items:center}
      .btn{
        display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:12px;
        font-weight:600;border:1px solid var(--ring);text-decoration:none;color:var(--text);
        background:linear-gradient(180deg,rgba(255,255,255,.03),rgba(255,255,255,.01));
        transition:transform .15s ease, box-shadow .2s ease, border-color .2s ease;
        outline:none;
      }
      .btn:hover{transform:translateY(-1px);box-shadow:0 8px 28px rgba(0,0,0,.25)}
      .btn:focus-visible{box-shadow:0 0 0 3px rgba(59,130,246,.35)}
      .btn-primary{background:var(--primary);border-color:transparent;color:white}
      .btn-primary:hover{background:var(--primary-600)}
      .btn-ghost{background:transparent}
      .row{display:grid;grid-template-columns:1.15fr .85fr;gap:22px}
      @media (max-width:960px){.row{grid-template-columns:1fr}}
      .glass{
        background:
          radial-gradient(1200px 400px at -10% -20%, var(--glow), transparent 60%),
          linear-gradient(180deg,rgba(255,255,255,.035),rgba(255,255,255,.015));
        border:1px solid var(--ring); border-radius:20px; padding:22px;
        box-shadow:0 10px 30px rgba(0,0,0,.28), inset 0 1px 0 rgba(255,255,255,.03);
      }
      .card{
        background:linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015));
        border:1px solid var(--ring);border-radius:var(--br);padding:18px;
      }
      [data-theme="light"] .card{
        background:linear-gradient(180deg, rgba(15,23,42,.02), rgba(15,23,42,.01));
      }
      .card + .card{margin-top:12px}
      .h1{font-size:30px;line-height:1.15;margin:6px 0 6px;font-weight:700}
      .muted{color:var(--muted)}
      .pill{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#cbd5e1;border:1px solid var(--ring);padding:4px 8px;border-radius:999px;background:rgba(255,255,255,.03)}
      [data-theme="light"] .pill{color:#334155;background:rgba(15,23,42,.02)}
      .hero-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
      .modules{margin-top:14px}
      .module-title{font-size:12px;color:#9fb0c2;margin-bottom:8px;letter-spacing:.4px;text-transform:uppercase}
      .module-desc{font-size:14px;color:#aab1b8}
      .module-row{display:flex;justify-content:space-between;align-items:center;gap:12px}
      .module-name{font-weight:600}
      .kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:14px}
      @media (max-width:520px){.kpis{grid-template-columns:1fr 1fr}}
      .kpi{background:linear-gradient(180deg,rgba(255,255,255,.03),rgba(255,255,255,.01));border:1px solid var(--ring); border-radius:12px; padding:12px;}
      [data-theme="light"] .kpi{background:linear-gradient(180deg,rgba(15,23,42,.02),rgba(15,23,42,.01))}
      .kpi .label{font-size:12px;color:#a1a9b0}
      .kpi .value{font-size:22px;font-weight:700;margin-top:4px}
      .spark{
        margin-top:10px;height:40px;width:100%;border-radius:10px;
        background:linear-gradient(180deg,rgba(255,255,255,.02),rgba(255,255,255,.01)),
                   repeating-linear-gradient(90deg, transparent 0 10px, rgba(255,255,255,.03) 10px 11px);
        position:relative;overflow:hidden;border:1px solid var(--ring);
      }
      [data-theme="light"] .spark{
        background:linear-gradient(180deg,rgba(15,23,42,.02),rgba(15,23,42,.01)),
                   repeating-linear-gradient(90deg, transparent 0 10px, rgba(15,23,42,.06) 10px 11px);
      }
      .spark::after{
        content:"";position:absolute;inset:auto -30% 6px -30%;height:2px;
        background:linear-gradient(90deg, transparent, var(--primary), transparent);
        filter:blur(.6px);animation:move 2.2s linear infinite;
      }
      @keyframes move{from{transform:translateX(-40%)} to{transform:translateX(40%)}}
      footer{margin-top:28px;color:#8b8b93;font-size:12px;border-top:1px solid var(--ring);padding-top:14px;display:flex;align-items:center;gap:8px}
      .footer-logo{width:14px;height:14px;border-radius:999px;opacity:.9}

      /* =========================
         UI: selector de tema
      ==========================*/
      .theme-switch{display:flex;gap:8px;align-items:center;}
      .swatch{
        width:24px;height:24px;border-radius:999px;border:1px solid var(--ring);
        display:inline-flex;align-items:center;justify-content:center;cursor:pointer;position:relative;
        background:linear-gradient(135deg,var(--primary),transparent 70%);
      }
      .swatch[data-name="indigo"]{background:conic-gradient(from 0deg,#3b82f6, #8b5cf6)}
      .swatch[data-name="emerald"]{background:conic-gradient(from 0deg,#10b981, #14b8a6)}
      .swatch[data-name="rose"]{background:conic-gradient(from 0deg,#f43f5e, #a855f7)}
      .swatch[data-name="amber"]{background:conic-gradient(from 0deg,#f59e0b, #fb7185)}
      .swatch[data-name="graphite"]{background:conic-gradient(from 0deg,#818cf8, #64748b)}
      .swatch[data-name="light"]{background:conic-gradient(from 0deg,#3b82f6, #94a3b8)}
      .swatch:focus-visible{outline:3px solid var(--primary); outline-offset:2px}
      .swatch[aria-current="true"]::after{
        content:""; position:absolute; inset:4px; border-radius:999px; border:2px solid white; mix-blend-mode:overlay;
      }

      @media (prefers-reduced-motion: reduce){
        .btn,.spark::after{animation:none;transition:none}
        .btn:hover{transform:none}
      }
    </style>

    <script>
      (function(){
        const KEY = 'inventory-tec-theme';
        const root = document.documentElement;
        const saved = localStorage.getItem(KEY);

        function applyTheme(name){
          root.setAttribute('data-theme', name);
          localStorage.setItem(KEY, name);
          // Ajusta el color de la barra del navegador en móviles
          const m = document.querySelector('meta[name="theme-color"]') || Object.assign(document.createElement('meta'), {name:'theme-color'});
          m.content = getComputedStyle(document.documentElement).getPropertyValue('--panel')?.trim() || '#0f1115';
          if (!m.parentNode) document.head.appendChild(m);
          // Actualiza estado visual de los botones
          document.querySelectorAll('.swatch').forEach(el=>{
            el.setAttribute('aria-current', el.dataset.name===name ? 'true' : 'false');
          });
        }

        // Determina tema inicial
        let initial = saved;
        if(!initial){
          const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          initial = prefersDark ? (document.documentElement.dataset.theme || 'indigo') : 'light';
        }
        applyTheme(initial);

        // Exponer para el listener del click (delegation)
        window.__setTheme = applyTheme;
      })();
    </script>
  </head>
  <body>
    <div class="container">
      {{-- HEADER --}}
      <header class="header" role="banner">
        <div class="brand">
          <img
            src="{{ asset('img/it/logo-256.png') }}"
            alt="Inventory Tech"
            class="logo"
            width="28"
            height="28"
            decoding="async"
          />
          <strong class="title">Inventario IT</strong>
          <span class="tag" aria-label="Versión">v1</span>
        </div>

        <nav aria-label="Opciones">
          {{-- Selector de tema (accesible) --}}
          <div class="theme-switch" role="group" aria-label="Cambiar tema">
            <button class="swatch" data-name="indigo"  title="Indigo (predeterminado)" aria-label="Tema Indigo"  onclick="__setTheme('indigo')"></button>
            <button class="swatch" data-name="emerald" title="Emerald"               aria-label="Tema Emerald" onclick="__setTheme('emerald')"></button>
            <button class="swatch" data-name="rose"    title="Rose"                  aria-label="Tema Rose"    onclick="__setTheme('rose')"></button>
            <button class="swatch" data-name="amber"   title="Amber"                 aria-label="Tema Amber"   onclick="__setTheme('amber')"></button>
            <button class="swatch" data-name="graphite"title="Graphite"              aria-label="Tema Graphite"onclick="__setTheme('graphite')"></button>
            <button class="swatch" data-name="light"   title="Claro"                 aria-label="Tema Claro"   onclick="__setTheme('light')"></button>
          </div>

          @if (Route::has('login'))
            @auth
              <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Ir al dashboard</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-ghost">Iniciar sesión</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta</a>
              @endif
            @endauth
          @endif
        </nav>
      </header>

      {{-- CONTENIDO --}}
      <main class="row" role="main">
        {{-- Columna izquierda: HERO + módulos --}}
        <section class="glass" aria-labelledby="hero-title">
          <div class="card" style="background:linear-gradient(180deg,rgba(59,130,246,.08),rgba(59,130,246,.02));">
            <div class="pill" aria-hidden="true">Admin • Inventario</div>

            {{-- Título con logo al lado --}}
            <div style="display:flex; align-items:center; gap:12px; margin-top:6px">
              <img src="{{ asset('img/it/logo-256.png') }}" alt="Inventory Tech" width="40" height="40" style="border-radius:999px"/>
              <h1 id="hero-title" class="h1" style="margin:0">Administra tu inventario de activos</h1>
            </div>

            <p class="muted" style="margin:8px 0 0">
              Dispositivos, tipos y especificaciones dinámicas (RAM, CPU, IMEI), asignaciones a usuarios y estados — todo en un solo lugar.
            </p>

            @auth
              <div class="hero-actions">
                <a href="{{ route('devices.create') }}" class="btn btn-primary" aria-label="Crear nuevo dispositivo">➕ Nuevo dispositivo</a>
                <a href="{{ route('devices.index') }}" class="btn" aria-label="Abrir inventario">📦 Inventario</a>
                <a href="{{ route('device-types.index') }}" class="btn" aria-label="Ver tipos de dispositivos">🧩 Tipos</a>
                @if (Route::has('employees.index'))
                  <a href="{{ route('employees.index') }}" class="btn" aria-label="Abrir empleados">👤 Empleados</a>
                @endif
              </div>
            @else
              <p class="muted" style="margin-top:10px">
                Usa los botones de la esquina superior derecha para iniciar sesión o crear una cuenta.
              </p>
            @endauth
          </div>

          {{-- KPIs --}}
          <div class="kpis" role="region" aria-label="Indicadores clave">
            <div class="kpi">
              <div class="label">Activos totales</div>
              <div class="value">—</div>
              <div class="spark" aria-hidden="true"></div>
            </div>
            <div class="kpi">
              <div class="label">Con asignación</div>
              <div class="value">—</div>
              <div class="spark" aria-hidden="true"></div>
            </div>
            <div class="kpi">
              <div class="label">Stock bajo</div>
              <div class="value">—</div>
              <div class="spark" aria-hidden="true"></div>
            </div>
          </div>

          {{-- Módulos --}}
          <div class="modules">
            <div class="module-title">Módulos</div>

            <div class="card">
              <div class="module-row">
                <div>
                  <div class="module-name">Dispositivos</div>
                  <div class="module-desc">Alta, edición, filtros y especificaciones dinámicas.</div>
                </div>
                <div style="display:flex;gap:8px">
                  <a href="{{ route('devices.index') }}" class="btn btn-ghost" aria-label="Abrir módulo dispositivos">Abrir</a>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="module-row">
                <div>
                  <div class="module-name">Tipos</div>
                  <div class="module-desc">Crea grupos/campos y valida con reglas de Laravel.</div>
                </div>
                <div style="display:flex;gap:8px">
                  <a href="{{ route('device-types.index') }}" class="btn btn-ghost" aria-label="Abrir módulo tipos">Abrir</a>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="module-row">
                <div>
                  <div class="module-name">Empleados</div>
                  <div class="module-desc">Catálogo con código, cédula, sede y estado.</div>
                </div>
                <div style="display:flex;gap:8px">
                  @if (Route::has('employees.index'))
                    <a href="{{ route('employees.index') }}" class="btn btn-ghost" aria-label="Abrir módulo empleados">Abrir</a>
                  @endif
                </div>
              </div>
            </div>

            @guest
              <div class="card" style="background:var(--card-2)">
                <div class="muted">Inicia sesión para acceder al inventario y a los módulos de administración.</div>
              </div>
            @endguest
          </div>

          <footer>
            <img src="{{ asset('img/it/favicon-16.png') }}" alt="IT" class="footer-logo" width="14" height="14"/>
            © {{ now()->year }} Inventario IT — Laravel Starter Kit
          </footer>
        </section>

        {{-- Columna derecha: Resumen visual / espacio para métricas o gráfico --}}
        <aside class="glass" style="min-height:260px;display:flex;align-items:center;justify-content:center;">
          <div class="card" style="
              width:100%;aspect-ratio:16/9;display:flex;flex-direction:column;gap:10px;
              align-items:stretch;justify-content:center;padding:16px;
              background:
                radial-gradient(700px 320px at 30% -10%, var(--glow), transparent 60%),
                radial-gradient(700px 320px at 80% 120%, var(--accentB), transparent 60%),
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01));
            ">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div style="font-weight:700;color:#cbd5e1">Resumen</div>
              <span class="tag">placeholder</span>
            </div>

            {{-- Placeholder de gráfico (SVG minimal) --}}
            <div style="flex:1;border:1px dashed var(--dash);border-radius:12px;position:relative;overflow:hidden">
              <svg viewBox="0 0 100 36" preserveAspectRatio="none" width="100%" height="100%">
                <polyline fill="none" stroke="var(--primary)" stroke-width="2"
                  points="0,30 10,28 20,26 30,27 40,22 50,24 60,18 70,14 80,16 90,10 100,12"/>
                <circle cx="90" cy="10" r="1.6" fill="var(--primary)"/>
              </svg>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
              <div class="pill" title="Última sincronización">🕒 Última sync: —</div>
              <div class="pill" title="Fuente de datos">🗄️ DB: no conectada</div>
            </div>
          </div>
        </aside>
      </main>
    </div>
  </body>
</html>
