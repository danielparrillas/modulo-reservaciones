// 🖌️ AntDesign
import { message } from "antd";
import "antd/dist/reset.css";
// 🌐 Librerias de terceros
import { useEffect } from "react";
// 😁 Componentes y funciones propias
import TableServicios from "./components/TableServicios";
import TabServicio from "./components/TabServicio";
import { useAppStore } from "../../hooks/appStore";

//⚙️ algunos settings
message.config({ top: 50 }); // para que los mensajes aparezcan 50px mas abajo

function App() {
  const { setHeight, setWidth, vista } = useAppStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);

  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };

  return (
    <div
      className="flex flex-col p-2 md:p-6 w-full overflow-auto text-neutral-700 h-full"
      style={{
        height: window.innerHeight - 50,
      }}
    >
      {vista === "lista" ? <TableServicios /> : <TabServicio />}
    </div>
  );
}

export default App;
