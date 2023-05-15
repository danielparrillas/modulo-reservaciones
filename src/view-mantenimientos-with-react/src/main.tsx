import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App.tsx";
import Root from "./Routes/root.tsx";
import ErrorPage from "./ErrorPage.tsx";
import "./index.css";
import { createBrowserRouter, RouterProvider } from "react-router-dom";

const router = createBrowserRouter([
  {
    path: "/reservaciones/views/lugares-dev",
    element: <Root />,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/lugares-dev/mantenimientos",
    element: <App />,
    errorElement: <ErrorPage />,
  },
  {
    path: "/reservaciones/views/lugares-dev/dashboard",
    element: <h1>Dashboard</h1>,
    errorElement: <h1>Dashboard</h1>,
  },
]);

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
);
