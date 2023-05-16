// AntDesign
import {
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  PlusOutlined,
} from "@ant-design/icons";
import { Table, Tag, Modal, Space, Button } from "antd";
import { ColumnsType } from "antd/es/table";
// Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// Componentes y funciones propias
import { useLugarStore } from "../hooks/lugarStore";

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
    width: "40%",
    sorter: (a: any, b: any) => a.nombre.localeCompare(b.nombre),
  },
  {
    title: "Acampar",
    dataIndex: "acampar",
    key: "acampar",
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

export default function LugaresPage() {
  const [lugares, setLugares] = useState<Lugar[]>([]);
  const { setLugar, setModo } = useLugarStore();

  const getLugares = async () => {
    await axios
      .get("/reservaciones/app/api/lugares")
      .then((response) => setLugares(convertirDataLugares(response.data.data)))
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

  useEffect(() => {
    getLugares();
  }, []);

  return (
    <div className="flex flex-col gap-4 h-full">
      <Space wrap>
        <Button
          type="primary"
          icon={<PlusOutlined />}
          onClick={() => {
            //setVista("tabs"); //! corregir
            setModo("nuevo");
          }}
        >
          Agregar nuevo
        </Button>
      </Space>
      <div className="h-full bg-white rounded-md">
        <Table
          dataSource={lugares}
          columns={columns}
          pagination={false}
          scroll={{ y: window.innerHeight - 160 }}
          onRow={(record) => {
            return {
              onClick: () => {
                //setVista("tabs"); //! corregir
                setLugar(record);
                setModo("edicion");
              },
            };
          }}
          rowClassName="cursor-pointer"
        />
      </div>
    </div>
  );
}
