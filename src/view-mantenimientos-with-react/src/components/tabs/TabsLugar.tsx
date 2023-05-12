import { useAppStore } from "../../hooks/appStore";
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs } from "antd";
import { useLugarStore } from "../../hooks/lugarStore";
import TabLugarInformacion from "./TabLugarInformacion";

export default function TabsLugar() {
  const { width, vista, setVista } = useAppStore();
  const { modo } = useLugarStore();

  return (
    <div
      className={
        `${vista !== "tabs" && "hidden"}` + " flex flex-col gap-4 h-full"
      }
    >
      <div className="flex gap-3">
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => setVista("table")}
          className=""
        >
          Ver todos los lugares
        </Button>
      </div>
      <Tabs
        defaultActiveKey="1"
        tabPosition={width < 700 ? "top" : "left"}
        items={[
          {
            key: "1",
            label: `Información`,
            children: <TabLugarInformacion />,
          },
          {
            key: "2",
            label: `Servicios`,
            children: `Content of Tab Pane 2`,
            disabled: modo === "nuevo",
          },
          {
            key: "3",
            label: `Periodos inactivos`,
            children: `Content of Tab Pane 3`,
            disabled: modo === "nuevo",
          },
        ]}
        className="bg-white p-4 rounded-md h-full overflow-auto"
      />
    </div>
  );
}
