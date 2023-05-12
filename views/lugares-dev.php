<!DOCTYPE html>
<html lang="es">

<head>
  <script type="module">
    import RefreshRuntime from "http://localhost:5173/reservaciones/views/@react-refresh"
    RefreshRuntime.injectIntoGlobalHook(window)
    window.$RefreshReg$ = () => { }
    window.$RefreshSig$ = () => (type) => type
    window.__vite_plugin_react_preamble_installed__ = true
  </script>

  <script type="module" src="http://localhost:5173/reservaciones/views/@vite/client"></script>

  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="http://localhost:5173/reservaciones/views/vite.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservaciones</title>

</head>

<body>
  <div id="root">
  </div>
  <script type="module" src="http://localhost:5173/reservaciones/views/src/main.tsx"></script>
</body>

</html>