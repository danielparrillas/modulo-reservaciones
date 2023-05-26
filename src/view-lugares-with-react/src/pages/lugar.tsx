// 🖌️ AntDesign
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs, message, Empty } from "antd";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
// 😁 Componentes y funciones propias
import { useAppStore } from "../hooks/appStore";
import { useLugarStore } from "../hooks/lugarStore";
import TabLugarInformacion from "../components/tabs/TabLugarInformacion";
import TabLugarDisponibilidades from "../components/tabs/TabLugarDisponibilidades";
import TabPeriodosDeshabilitados from "../components/tabs/TabPeriodosDeshabilitados";

export default function LugarPage() {
  const { setVista, width } = useAppStore();
  const { modo, setModo } = useLugarStore();
  const [lugarId, setLugarId] = useState<number>();
  const { lugarSeleccionado, tab, setTab } = useLugarStore();

  useEffect(() => {
    if (!lugarSeleccionado) setModo("nuevo");
    else {
      setLugarId(lugarSeleccionado);
      setModo("edicion");
    }
  }, [lugarSeleccionado]);

  return (
    <div className=" flex flex-col gap-4 h-full">
      <div className="flex gap-4">
        <h2 className="font-semibold">Lugares</h2>
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => setVista("lista")}
          disabled={modo === "guardando"}
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
            children: <TabLugarInformacion lugarId={lugarId} />,
            disabled: modo === "guardando",
          },
          {
            key: "2",
            label: `Disponibilidades`,
            children:
              lugarId !== undefined ? (
                <TabLugarDisponibilidades lugarId={lugarId} />
              ) : (
                <Empty />
              ),
            disabled: modo === "nuevo" || modo === "guardando",
          },
          {
            key: "3",
            label: `Periodos inactivos`,
            children:
              lugarId !== undefined ? (
                <TabPeriodosDeshabilitados lugarId={lugarId} />
              ) : (
                <Empty />
              ),
            disabled: modo === "nuevo" || modo === "guardando",
          },
        ]}
        className="bg-white p-4 rounded-md h-full overflow-auto"
        onChange={(key) => {
          console.log(key);
          setTab(key);
        }}
      />
    </div>
  );
}
