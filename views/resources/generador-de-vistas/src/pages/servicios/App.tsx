// 🖌️ AntDesign
import { PlusOutlined } from "@ant-design/icons";
import { Button, message } from "antd";
import "antd/dist/reset.css";
// 🌐 Librerias de terceros
import { useEffect } from "react";
// 😁 Componentes y funciones propias
import TableServicios from "./components/TableServicios";
import ServicioForm from "./components/ServicioForm";
import { useAppStore } from "../../hooks/appStore";
import { useServicioStore } from "../../hooks/servicioStore";
useServicioStore;

//⚙️ algunos settings
message.config({ top: 50 }); // para que los mensajes aparezcan 50px mas abajo

function App() {
  const { setHeight, setWidth } = useAppStore();
  const { setIsOpenForm, setDisponibilidadGrupoId, setServicioSeleccionadoId } =
    useServicioStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);

  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };
  console.log(document.cookie);

  return (
    <div
      className="flex flex-col p-2 md:p-6 w-full overflow-auto text-neutral-700 h-full"
      style={{
        height: window.innerHeight - 50,
      }}
    >
      <div className="flex flex-col gap-4 h-full">
        <div className="flex gap-8">
          <h2 className="font-semibold">Servicios</h2>
          <Button
            type="primary"
            icon={<PlusOutlined />}
            onClick={() => {
              // limpiamos los valores
              setDisponibilidadGrupoId();
              setServicioSeleccionadoId();
              setIsOpenForm(true);
            }}
          >
            Agregar nuevo
          </Button>
        </div>
        <div className="h-full bg-white rounded-md">
          <TableServicios />
        </div>
      </div>
      <ServicioForm />
    </div>
  );
}

export default App;
