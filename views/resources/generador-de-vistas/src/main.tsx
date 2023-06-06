import React from "react";
import ReactDOM from "react-dom/client";
import "./index.css";

//👀 SOLO deja comentada 1 pag que
// esa sera la que podras ver y construir para produccion
import App from "./pages/lugares/App.tsx"; //1️⃣ lugares
// import App from "./pages/servicios/App.tsx"; //2️⃣ servicios

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
