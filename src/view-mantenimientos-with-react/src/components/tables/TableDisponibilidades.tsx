import { Table, Tag, Popconfirm } from "antd";
import { EditFilled } from "@ant-design/icons";
import { ColumnsType } from "antd/es/table";

const colums: ColumnsType<any> = [
  {
    title: "Disponibilidad de",
    dataIndex: "disponibilidad",
    sorter: (a: any, b: any) =>
      a.disponibilidad.localeCompare(b.disponibilidad),
  },
  {
    title: "Cantidad",
    dataIndex: "cantidad",
    align: "right",
    sorter: (a: any, b: any) => a.cantidad - b.cantidad,
  },
  {
    title: "",
    className: "text-center",
    render: () => {
      return (
        <Popconfirm
          title="Eliminar periodo deshabilitado"
          description="Al eliminar el periodo deshabilitado el lugar volvera a estar disponible para estas fechas"
          okText="Si"
          cancelText="No"
        >
          <Tag color="warning" icon={<EditFilled />} className="cursor-pointer">
            Editar
          </Tag>
        </Popconfirm>
      );
    },
  },
];

const dataSource = [
  {
    key: "pd-1",
    disponibilidad: "asdfjsajfkls sdjf",
    cantidad: 12,
  },
  { key: "pd-2", disponibilidad: "asdfjsajfkls sdjf", cantidad: 122 },
  { key: "pd-3", disponibilidad: "asdfjsajfkls sdjf", cantidad: 124 },
  { key: "pd-4", disponibilidad: "asdfjsajfkls sdjf", cantidad: 42 },
  { key: "pd-5", disponibilidad: "asdfjsajfkls sdjf", cantidad: 52 },
  { key: "pd-6", disponibilidad: "asdfjsajfkls sdjf", cantidad: 60 },
  { key: "pd-7", disponibilidad: "asdfjsajfkls sdjf", cantidad: 70 },
  { key: "pd-8", disponibilidad: "asdfjsajfkls sdjf", cantidad: 82 },
];

export default function TableDisponibilidades() {
  return (
    <Table
      columns={colums}
      dataSource={dataSource}
      pagination={false}
      scroll={{ y: window.innerHeight - 420 }}
      size="middle"
    />
  );
}
