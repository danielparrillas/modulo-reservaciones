import { useEffect } from "react";
import "antd/dist/reset.css";
import { useAppStore } from "./hooks/appStore";
import LugaresPage from "./pages/lugares";
import LugarPage from "./pages/lugar";
import { useLugarStore } from "./hooks/lugarStore";

function App() {
  const { vista, setHeight, setWidth } = useAppStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);
  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };
  return (
    <div className="flex flex-col p-2 md:p-6 bg-transparent w-full h-full overflow-auto text-slate-600">
      {vista === "lista" ? <LugaresPage /> : <LugarPage />}
    </div>
  );
}

export default App;
