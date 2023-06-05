// 🖌️ AntDesign
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs, Empty } from "antd";
// 🌐 Librerias de terceros
// 😁 Componentes y funciones propias
import { useAppStore } from "../../../hooks/appStore";
import { useLugarStore } from "../../../hooks/lugarStore";
import TabLugarInformacion from "./tabs/TabLugarInformacion";
import TabLugarDisponibilidades from "./tabs/TabLugarDisponibilidades";
import TabPeriodosDeshabilitados from "./tabs/TabPeriodosDeshabilitados";
// import TabServicios from "./tabs/TabServicios";

export default function Detalle() {
  const { setVista, width } = useAppStore();
  const {} = useLugarStore();
  const { lugarSeleccionado, estaGuardando, setTab } = useLugarStore();

  return (
    <div className=" flex flex-col gap-4 h-full">
      <div className="flex gap-4">
        <h2 className="font-semibold">Lugares</h2>
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => setVista("lista")}
          disabled={estaGuardando}
        >
          Ver todos los lugares
        </Button>
      </div>
      <Tabs
        defaultActiveKey="1"
        tabPosition={width < 960 ? "top" : "left"}
        items={[
          {
            key: "1",
            label: `Información`,
            children: <TabLugarInformacion />,
            disabled: estaGuardando,
          },
          {
            key: "2",
            label: `Disponibilidades`,
            children:
              lugarSeleccionado !== undefined ? (
                <TabLugarDisponibilidades lugarId={lugarSeleccionado} />
              ) : (
                <Empty />
              ),
            // disabled: modo === "nuevo" || modo === "guardando",
            disabled: !lugarSeleccionado || estaGuardando,
          },
          {
            key: "3",
            label: `Periodos inactivos`,
            children:
              lugarSeleccionado !== undefined ? (
                <TabPeriodosDeshabilitados lugarId={lugarSeleccionado} />
              ) : (
                <Empty />
              ),
            // disabled: modo === "nuevo" || modo === "guardando",
            disabled: !lugarSeleccionado || estaGuardando,
          },
        ]}
        className="bg-white p-4 rounded-md h-full overflow-auto"
        onChange={(key) => {
          // console.log(key);
          setTab(key);
        }}
      />
    </div>
  );
}
