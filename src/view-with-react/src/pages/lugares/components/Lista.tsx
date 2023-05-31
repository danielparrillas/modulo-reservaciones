// 🖌️ AntDesign
import {
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  PlusOutlined,
  ArrowRightOutlined,
} from "@ant-design/icons";
import { Table, Tag, Modal, Button } from "antd";
import { ColumnsType } from "antd/es/table";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
import { useLugarStore } from "../hooks/lugarStore";
import { useAppStore } from "../hooks/appStore";
// 😁 Componentes y funciones propias

interface Lugar {
  key: string;
  id: number;
  nombre: string;
  activo: string;
  acampar: string;
}

const columns: ColumnsType<any> = [
  {
    title: "Lugar",
    dataIndex: "nombre",
    key: "lugarnombre",
    sorter: (a: any, b: any) => a.nombre.localeCompare(b.nombre),
  },
  {
    title: "Acampar",
    dataIndex: "acampar",
    key: "acampar",
    responsive: ["md"],
    render: (permitido: boolean) => {
      if (permitido) {
        return <Tag color="processing">Permitido</Tag>;
      } else {
        return <Tag color="warning">Prohibido</Tag>;
      }
    },
  },
  {
    title: "Activo",
    dataIndex: "activo",
    key: "activo",
    render: (estado: boolean) => {
      if (estado) {
        return (
          <Tag icon={<CheckCircleOutlined />} color="success">
            Activo
          </Tag>
        );
      } else {
        return (
          <Tag icon={<ExclamationCircleOutlined />} color="default">
            Inactivo
          </Tag>
        );
      }
    },
  },
];

export default function Lista() {
  const [lugares, setLugares] = useState<Lugar[]>([]);
  const { setLugar } = useLugarStore();
  const { setVista } = useAppStore();

  useEffect(() => {
    getLugares();
  }, []);

  const getLugares = async () => {
    await axios
      .get("/reservaciones/app/services/lugares")
      .then((response) => {
        // console.log(response); //👀
        setLugares(convertirDataLugares(response.data.data));
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Error al llamar los datos",
          content: error.message,
        });
      });
  };

  const convertirDataLugares = (lugares: any[]) => {
    // console.log(lugares);
    return lugares.map((lugar) => ({
      key: `row-lugar-${lugar.lugarId}`,
      id: lugar.lugarId,
      nombre: lugar.nombre,
      activo: lugar.activo,
      acampar: lugar.permiteAcampar,
    }));
  };

  return (
    <div className="flex flex-col gap-4 h-full">
      <div className="flex gap-8">
        <h2 className="font-semibold">Lugares</h2>
        <Button
          type="primary"
          icon={<PlusOutlined />}
          // onClick={() => navigate("nuevo")} //⚠️⚠️⚠️
        >
          Agregar nuevo
        </Button>
      </div>
      <div className="h-full bg-white rounded-md">
        <Table
          dataSource={lugares}
          columns={[
            ...columns,
            {
              title: "",
              dataIndex: "id",
              key: "actions",
              align: "center",
              render: (_, record) => (
                <ArrowRightOutlined
                  className="w-full"
                  onClick={() => {
                    setVista("detalle");
                    setLugar(record.id);
                  }}
                />
              ),
            },
          ]}
          pagination={false}
          scroll={{ y: window.innerHeight - 230 }}
        />
      </div>
    </div>
  );
}
