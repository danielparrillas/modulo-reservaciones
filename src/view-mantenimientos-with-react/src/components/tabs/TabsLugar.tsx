import { useLayoutStore } from "../../hooks/layoutStore";
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs } from "antd";
import { useLugarStore } from "../../hooks/lugarStore";
import TabLugarInformacion from "./TabLugarInformacion";
import TabLugarDisponibilidades from "./TabLugarDisponibilidades";
import TabPeriodosDeshabilitados from "./TabPeriodosDeshabilitados";

export default function TabsLugar() {
  const { width } = useLayoutStore();
  const { modo } = useLugarStore();

  return (
    <div className=" flex flex-col gap-4 h-full">
      <div className="flex gap-3">
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => {
            //ysetVista("table"); //! corregir
          }}
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
            children: <TabLugarInformacion />,
          },
          {
            key: "2",
            label: `Disponibilidades`,
            children: <TabLugarDisponibilidades />,
            disabled: modo === "nuevo" || modo === "guardando",
          },
          {
            key: "3",
            label: `Periodos inactivos`,
            children: <TabPeriodosDeshabilitados />,
            disabled: modo === "nuevo" || modo === "guardando",
          },
        ]}
        className="bg-white p-4 rounded-md h-full overflow-auto"
      />
    </div>
  );
}
