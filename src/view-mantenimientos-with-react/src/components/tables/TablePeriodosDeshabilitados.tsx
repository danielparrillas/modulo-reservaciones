import { Table, Tag, Popconfirm } from "antd";
import { DeleteFilled } from "@ant-design/icons";
import { ColumnsType } from "antd/es/table";

const colums: ColumnsType<any> = [
  {
    title: "Inicio",
    dataIndex: "inicio",
    sorter: (a: any, b: any) => a.inicio.localeCompare(b.inicio),
  },
  {
    title: "Fin",
    dataIndex: "fin",
    sorter: (a: any, b: any) => a.fin.localeCompare(b.fin),
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
          <Tag color="error" icon={<DeleteFilled />} className="cursor-pointer">
            Eliminar
          </Tag>
        </Popconfirm>
      );
    },
  },
];

const dataSource = [
  { key: "pd-1", inicio: "1970-01-01", fin: "2023-01-01" },
  { key: "pd-2", inicio: "1980-01-01", fin: "2023-02-01" },
  { key: "pd-3", inicio: "1990-01-01", fin: "2023-03-01" },
  { key: "pd-4", inicio: "2000-01-01", fin: "2023-04-01" },
  { key: "pd-5", inicio: "2001-01-01", fin: "2023-05-01" },
  { key: "pd-6", inicio: "2003-01-01", fin: "2023-06-01" },
  { key: "pd-7", inicio: "2004-01-01", fin: "2023-07-01" },
  { key: "pd-8", inicio: "2005-01-01", fin: "2023-08-01" },
];

export default function TablePeriodosDeshabilitados() {
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
