<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oficina Mecânica - Documentação da API</title>
  <meta name="description" content="Documentação interactiva do Sistema de gestão de Assistência técnica">
  <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
    .topbar-wrapper { display: none !important; }
    #swagger-ui .topbar { display: none !important; }
    .custom-header {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      color: #fff;
      padding: 24px 32px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .custom-header svg { flex-shrink: 0; }
    .custom-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: 0.5px; }
    .custom-header p { font-size: 0.875rem; opacity: 0.75; margin-top: 4px; }
    .badges { display: flex; gap: 8px; margin-top: 8px; }
    .badge {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 999px;
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .badge-green { background: #22c55e; color: #fff; }
    .badge-blue { background: #3b82f6; color: #fff; }
    .badge-orange { background: #f97316; color: #fff; }
    #swagger-ui { max-width: 1200px; margin: 0 auto; padding: 24px 16px; }
    .health-bar {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 12px 20px;
      margin: 0 auto 16px;
      max-width: 1200px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 0.875rem;
    }
    .health-status { font-weight: 600; }
    .health-ok { color: #16a34a; }
    .health-error { color: #dc2626; }
    .health-degraded { color: #d97706; }
    .health-link {
      margin-left: auto;
      color: #2563eb;
      text-decoration: none;
      font-size: 0.8rem;
    }
    .health-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="custom-header">
  <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="24" cy="24" r="24" fill="#0f3460"/>
    <path d="M14 34L20 28M20 28L26 22M26 22L30 18M30 18L34 14M30 18L34 22M30 18L26 14" stroke="#e94560" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="20" cy="28" r="3" fill="#e94560"/>
    <path d="M18 18C18 16.3431 19.3431 15 21 15H27C28.6569 15 30 16.3431 30 18V18.5H18V18Z" fill="#4fc3f7"/>
    <rect x="17" y="30" width="14" height="4" rx="2" fill="#4fc3f7"/>
  </svg>
  <div>
    <h1>Oficina Mecânica — Documentação</h1>
    <p>Sistema completo de gestão para oficinas mecânicas | MVC PHP + MySQL</p>
    <div class="badges">
      <span class="badge badge-green">v1.0.0</span>
      <span class="badge badge-blue">OpenAPI 3.0</span>
      <span class="badge badge-orange">PHP 8+</span>
    </div>
  </div>
</div>

<div class="health-bar" id="healthBar">
  <span>Estado da aplicação:</span>
  <span class="health-status" id="healthStatus">A verificar...</span>
  <a href="health.php" target="_blank" class="health-link">Ver detalhes do health check →</a>
</div>

<div id="swagger-ui"></div>

<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script>
  // Inicializar Swagger UI
  SwaggerUIBundle({
    url: './swagger.json',
    dom_id: '#swagger-ui',
    presets: [SwaggerUIBundle.presets.apis, SwaggerUIBundle.SwaggerUIStandalonePreset],
    layout: 'BaseLayout',
    deepLinking: true,
    displayRequestDuration: true,
    filter: true,
    tryItOutEnabled: true,
    requestInterceptor: (request) => {
      request.credentials = 'include';
      return request;
    }
  });

  // Health check dinâmico
  fetch('./health.php')
    .then(r => r.json())
    .then(data => {
      const el = document.getElementById('healthStatus');
      const classMap = { ok: 'health-ok', degraded: 'health-degraded', error: 'health-error' };
      const labelMap = { ok: '✓ Operacional', degraded: '⚠ Degradado', error: '✗ Com erros' };
      el.className = 'health-status ' + (classMap[data.status] || '');
      el.textContent = labelMap[data.status] || data.status;
    })
    .catch(() => {
      document.getElementById('healthStatus').textContent = '— Não disponível';
    });
</script>
</body>
</html>
