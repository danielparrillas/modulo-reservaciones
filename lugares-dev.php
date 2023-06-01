<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/vista/header.php"; ?>
<link rel="stylesheet" href="/vista/recursos/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
<!--CSS-->
<!--Javascript-->
<script src="/vista/recursos/media/js/jquery-1.10.2.js"></script>
<script src="/vista/recursos/media/js/bootstrap.js"></script>
<script type="text/javascript" src="/vista/recursos/js/jsmodelo/jsusuarioexterno.js"></script>
<link rel="stylesheet" href="/vista/recursos/css/select2.css">
<script type="text/javascript" src="/vista/recursos/js/select2.js"></script>
<script src="/vista/recursos/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/vista/recursos/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="/vista/recursos/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="/vista/dist/js/app.min.js"></script>

<!-- ⚠️ Modo desarrollo -->
<script type="module">
  import RefreshRuntime from "http://localhost:5173/@react-refresh"
  RefreshRuntime.injectIntoGlobalHook(window)
  window.$RefreshReg$ = () => {}
  window.$RefreshSig$ = () => (type) => type
  window.__vite_plugin_react_preamble_installed__ = true
</script>

<script type="module" src="http://localhost:5173/@vite/client"></script>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div id="root">
</div>
<script type="module" src="http://localhost:5173/src/main.tsx"></script>
</body>

</html>