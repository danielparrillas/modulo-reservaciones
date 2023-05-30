import { useEffect } from "react";
import "antd/dist/reset.css";
import { useAppStore } from "./hooks/appStore";
import Lista from "./components/Lista";
import Detalle from "./components/Detalle";

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
      {vista === "lista" ? <Lista /> : <Detalle />}
    </div>
  );
}

export default App;
