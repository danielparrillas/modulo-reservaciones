import "antd/dist/reset.css";
import { useAppStore } from "../../hooks/appStore";
import Lista from "./components/Lista";
import Detalle from "./components/Detalle";
import { useEffect } from "react";

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
    <div
      className="flex flex-col p-2 md:p-6 bg-transparent w-full overflow-auto text-neutral-700"
      style={{
        height: window.innerHeight - 50,
      }}
    >
      {vista === "lista" ? <Lista /> : <Detalle />}
    </div>
  );
}

export default App;
