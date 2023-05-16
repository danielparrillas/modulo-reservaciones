// 🖌️ AntDesign
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs, message } from "antd";
// 🌐 Librerias de terceros
import { useLocation } from "react-router-dom";
import { useNavigate } from "react-router-dom";
// 😁 Componentes y funciones propias
import { useLayoutStore } from "../hooks/layoutStore";
import { useLugarStore } from "../hooks/lugarStore";
import TabLugarInformacion from "../components/tabs/TabLugarInformacion";
import TabLugarDisponibilidades from "../components/tabs/TabLugarDisponibilidades";
import TabPeriodosDeshabilitados from "../components/tabs/TabPeriodosDeshabilitados";
import { useEffect, useState } from "react";

export default function LugarPage() {
  const { width } = useLayoutStore();
  const { modo, setModo } = useLugarStore();
  const { pathname } = useLocation();
  const [path, setPath] = useState<string | null>();
  const navigate = useNavigate();

  useEffect(() => {
    let url = pathname.split("/");
    let urlLast = url[url.length - 1];
    setPath(urlLast);

    if (urlLast === "nuevo") setModo("nuevo");
    else {
      let id = parseInt(urlLast);
      if (Number.isInteger(id)) setModo("edicion");
      else message.error("Formato de id incompatible");
    }
  }, []);

  return (
    <div className=" flex flex-col gap-4 h-full">
      <div className="flex gap-3">
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => {
            navigate("/reservaciones/views/lugares");
          }}
          disabled={modo === "guardando"}
        >
          Ver todos los lugares
        </Button>
        {path}
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
