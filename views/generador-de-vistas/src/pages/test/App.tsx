// 🖌️ AntDesign
import { message } from "antd";
import "antd/dist/reset.css";
// 🌐 Librerias de terceros
import { useEffect } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import { useAppStore } from "../../hooks/appStore";

//⚙️ algunos settings
message.config({ top: 50 }); // para que los mensajes aparezcan 50px mas abajo

function App() {
  const { setHeight, setWidth } = useAppStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
    test();
  }, []);

  const test = async () => {
    await axios
      .get("../reservaciones/api/lugares")
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.error(error);
      });
  };
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
    ></div>
  );
}

export default App;
