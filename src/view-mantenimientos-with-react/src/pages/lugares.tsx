// 🖌️ AntDesign
import {
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  PlusOutlined,
  ArrowRightOutlined,
} from "@ant-design/icons";
import { Table, Tag, Modal, Space, Button } from "antd";
import { ColumnsType } from "antd/es/table";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
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
  {
    title: "",
    dataIndex: "id",
    key: "actions",
    align: "center",
    render: (id: number) => (
      <Link to={`${id}`}>
        <ArrowRightOutlined className="w-full" />
      </Link>
    ),
  },
];

export default function LugaresPage() {
  const [lugares, setLugares] = useState<Lugar[]>([]);
  const navigate = useNavigate();

  useEffect(() => {
    //getLugares();
    guardar();
  }, []);

  const guardar = async () => {
    axios
      .post("http://localhost/reservaciones/app/api/lugares/", {
        nombre: 1,
        permiteAcampar: true,
        activo: "f ",
      })
      .then((response) => console.log(response));
  };

  const getLugares = async () => {
    await axios
      .get("/reservaciones/app/api/lugares")
      .then((response) => {
        console.log(response);
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
    console.log(lugares);
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
      <Space wrap>
        <Button
          type="primary"
          icon={<PlusOutlined />}
          onClick={() => navigate("nuevo")}
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
        />
      </div>
    </div>
  );
}
