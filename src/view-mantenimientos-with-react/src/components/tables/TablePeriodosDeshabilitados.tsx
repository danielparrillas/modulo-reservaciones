// 🖌️ AntDesign
import { Table, Tag, Popconfirm } from "antd";
import { DeleteFilled } from "@ant-design/icons";
import { ColumnsType } from "antd/es/table";
// 🌐 Librerias de terceros
import axios from "axios";
import { useEffect, useState } from "react";

type PeriodoDeshabilitados = {
  id: number;
  inicio: string;
  fin: string;
};
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
          description="Al eliminar el periodo deshabilitado el lugar volvera a estar disponible para estas fechas. ¿Quieres eliminarlo?"
          okText="Si"
          cancelText="No"
          overlayClassName="w-64"
        >
          <Tag color="error" icon={<DeleteFilled />} className="cursor-pointer">
            Eliminar
          </Tag>
        </Popconfirm>
      );
    },
  },
];
interface TablePeriodosDeshabilitadosProps {
  lugarId: number;
}
export default function TablePeriodosDeshabilitados({
  lugarId,
}: TablePeriodosDeshabilitadosProps) {
  const [periodos, setPeriodos] = useState<PeriodoDeshabilitados[]>([]);
  useEffect(() => {
    getAllPeriodosDeshabilitados();
  }, []);
  const getAllPeriodosDeshabilitados = async () => {
    await axios
      .get(`/reservaciones/app/api/lugares/${lugarId}/periodosDeshabilitados`)
      .then((response) => {
        console.log(response); //👀
        let data = response.data.map((item: PeriodoDeshabilitados) => ({
          key: `pd-${item.id}`,
          id: item.id,
          inicio: item.inicio,
          fin: item.fin,
        }));
        setPeriodos(data);
      })
      .catch((error) => {
        console.error(error);
      });
  };
  return (
    <Table
      columns={colums}
      dataSource={periodos}
      pagination={false}
      scroll={{ y: window.innerHeight - 430 }}
      size="middle"
    />
  );
}
