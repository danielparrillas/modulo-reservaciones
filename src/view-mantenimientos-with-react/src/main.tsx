import React from "react";
import ReactDOM from "react-dom/client";
import ErrorPage from "./pages/error-page.tsx";
import "./index.css";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LugaresPage from "./pages/lugares.tsx";
import ClientesApiPage from "./pages/clientes-api.tsx";
import ServiciosPage from "./pages/servicios.tsx";
import Layout from "./components/layout/Layout.tsx";
import LugarPage from "./pages/[lugar].tsx";

const router = createBrowserRouter([
  {
    path: "/reservaciones/views/mantenimientos",
    element: <h1>Mantenimientos</h1>,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/lugares",
    element: <LugaresPage />,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/lugares/:lugar",
    element: <LugarPage />,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/cliente-api",
    element: <ClientesApiPage />,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/servicios",
    element: <ServiciosPage />,
    errorElement: <ErrorPage />,
  },
]);

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(
  <React.StrictMode>
    <Layout>
      <RouterProvider router={router} />
    </Layout>
  </React.StrictMode>
);
