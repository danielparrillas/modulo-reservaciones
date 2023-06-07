// 🖌️ AntDesign
import { Tabs, Button } from "antd";
import { UnorderedListOutlined } from "@ant-design/icons";
// 😁 Componentes y funciones propias
import { useAppStore } from "../../../hooks/appStore";
import ServicioForm from "./ServicioForm";
import { useServicioStore } from "../../../hooks/servicioStore";

export default function TabServicio() {
  const { width } = useAppStore();
  const { estaGuardando } = useServicioStore();
  const { setVista } = useAppStore();

  return (
    <div className="flex flex-col gap-4 h-full">
      <div className="flex gap-8">
        <h2 className="font-semibold">Servicios</h2>
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => {
            // limpiamos los valores
            // setDisponibilidadGrupoId();
            // setServicioSeleccionadoId();
            // setIsOpenForm(true);
            setVista("lista");
          }}
        >
          Ver todos los servicios
        </Button>
      </div>
      <div className="h-full bg-white rounded-md p-4">
        <Tabs
          defaultActiveKey="1"
          tabPosition={width < 960 ? "top" : "left"}
          items={[
            {
              key: "1",
              label: "Información",
              children: <ServicioForm />,
              disabled: estaGuardando,
            },
          ]}
        />
      </div>
    </div>
  );
}
