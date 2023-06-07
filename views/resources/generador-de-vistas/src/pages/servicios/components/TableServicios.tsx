// 🖌️ AntDesign
import {
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  ArrowRightOutlined,
  PlusOutlined,
} from "@ant-design/icons";
import { Table, Tag, Modal, Button } from "antd";
import { ColumnsType } from "antd/es/table";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import { dollarString } from "../../../utils/formats";
import { useServicioStore } from "../../../hooks/servicioStore";
import { useAppStore } from "../../../hooks/appStore";

interface Servicio {
  nombre: string;
  descripcion: string;
  disponibilidadId: number;
  eliminado: boolean;
  id: number;
  precio: number;
}

export default function TableServicios() {
  const { setServicioSeleccionadoId, setDisponibilidadGrupoId } =
    useServicioStore();
  const { setVista } = useAppStore();
  const [data, setData] = useState<Servicio[]>([]);

  useEffect(() => {
    // actualizamos los datos cada vez que cambia el estado del formulario
    getServicios();
  }, []);
  const getServicios = async () => {
    await axios
      .get("/reservaciones/api/servicios")
      .then((response) => {
        // console.log(response); //👀
        setData(response.data);
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: error.message,
          content: "Error al traer los datos",
        });
      });
  };

  const columns: ColumnsType<Servicio> = [
    {
      title: "Servicio",
      dataIndex: "nombre",
      key: "servicio",
      className: "w-10",
      showSorterTooltip: { title: "Click para ordenar" },
      sorter: (a, b) => a.nombre.localeCompare(b.nombre),
    },
    {
      title: "Descripión",
      dataIndex: "descripcion",
      key: "descripcion",
      responsive: ["sm"],
    },
    {
      title: "Precio",
      dataIndex: "precio",
      key: "precio",
      width: 100,
      responsive: ["sm"],
      showSorterTooltip: { title: "Click para ordenar" },
      sorter: (a, b) => a.precio - b.precio,
      render: (precio: number) =>
        precio === 0 ? (
          <Tag color="green" className="font-bold">
            Gratis
          </Tag>
        ) : (
          <Tag color="green-inverse" className="font-bold">
            {dollarString.format(precio)}
          </Tag>
        ),
    },
    {
      title: "Activo",
      dataIndex: "eliminado",
      key: "eliminado",
      width: 110,
      render: (eliminado: boolean) => {
        if (!eliminado) {
          return (
            <Tag icon={<CheckCircleOutlined />} color="blue">
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
      width: 70,
      render: (_, record) => (
        <ArrowRightOutlined
          className="w-full hover:text-blue-500"
          onClick={() => {
            setServicioSeleccionadoId(record.id);
            // setIsOpenForm(true);
            setVista("detalle");
          }}
        />
      ),
    },
  ];
  return (
    <div className="flex flex-col gap-4 h-full">
      <div className="flex gap-8">
        <h2 className="font-semibold">Servicios</h2>
        <Button
          type="primary"
          icon={<PlusOutlined />}
          onClick={() => {
            // limpiamos los valores
            setDisponibilidadGrupoId();
            setServicioSeleccionadoId();
            setVista("detalle");
          }}
        >
          Agregar nuevo
        </Button>
      </div>
      <div className="h-full bg-white rounded-md">
        <Table
          pagination={false}
          scroll={{ y: window.innerHeight - 190 }}
          columns={columns}
          dataSource={data}
          rowKey={(item) => item.id}
        />
      </div>
    </div>
  );
}
