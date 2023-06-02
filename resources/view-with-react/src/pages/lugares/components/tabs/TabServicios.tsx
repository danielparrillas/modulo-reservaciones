// 🖌️ AntDesign
import {
  PlusOutlined,
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  ArrowRightOutlined,
} from "@ant-design/icons";
import { Table, Button, Tag, Divider } from "antd";
import { ColumnsType } from "antd/es/table";

const columns: ColumnsType<any> = [
  {
    title: "Servicio",
    dataIndex: "nombre",
    key: "servicio",
    className: "w-10",
    sorter: (a: any, b: any) => a.nombre.localeCompare(b.nombre),
  },
  {
    title: "Precio",
    dataIndex: "precio",
    key: "precio",
    width: 150,
    sorter: (a: any, b: any) => a.nombre.localeCompare(b.nombre),
  },
  {
    title: "Disponibilidad",
    dataIndex: "disponibilidad",
    key: "disponibilidad",
    responsive: ["md"],
    width: 150,
    render: (permitido: boolean) => {
      if (permitido) {
        return <Tag color="processing">Permitido</Tag>;
      } else {
        return <Tag color="warning">Prohibido</Tag>;
      }
    },
  },
  {
    title: "Tipo disponibilidad",
    dataIndex: "tipoDisponibilidad",
    key: "tipoDisponibilidad",
    width: 150,
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

interface TabServiciosProps {
  lugarId: number;
}
export default function TabServicios({}: TabServiciosProps) {
  return (
    <div className="text-neutral-600 flex flex-col p-4 gap-4">
      <div className="flex gap-4 items-center">
        <div className="flex items-center text-5xl">Servicios</div>
        <Button
          type="primary"
          size="middle"
          icon={<PlusOutlined />}
          onClick={() => {}} //⚠️⚠️⚠️
        ></Button>
      </div>
      <Divider />
      <Table
        pagination={false}
        scroll={{ y: window.innerHeight - 100 }}
        size="middle"
        columns={[
          ...columns,
          {
            title: "",
            dataIndex: "id",
            key: "actions",
            align: "center",
            width: 70,
            render: (_) => (
              <ArrowRightOutlined
                className="w-full hover:text-blue-500"
                onClick={() => {
                  // setVista("detalle");
                  // setLugarSeleccionado(record.id);
                }}
              />
            ),
          },
        ]}
      />
    </div>
  );
}
