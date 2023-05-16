// 🖌️ AntDesign
import { UnorderedListOutlined } from "@ant-design/icons";
import { Button, Tabs, Modal, message } from "antd";
// 🌐 Librerias de terceros
import { useLocation, useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import { useLayoutStore } from "../hooks/layoutStore";
import { useLugarStore } from "../hooks/lugarStore";
import TabLugarInformacion from "../components/tabs/TabLugarInformacion";
import TabLugarDisponibilidades from "../components/tabs/TabLugarDisponibilidades";
import TabPeriodosDeshabilitados from "../components/tabs/TabPeriodosDeshabilitados";

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
      if (Number.isInteger(id)) {
        setModo("edicion");
        getLugar(id);
      } else message.error("Formato de id incompatible");
    }
  }, []);

  const getLugar = async (id: number) => {
    await axios
      .get(`/reservaciones/app/api/lugares/${id}`)
      .then((response) => console.log(response))
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Error al llamar los datos",
          content: error.message,
        });
      });
  };

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
