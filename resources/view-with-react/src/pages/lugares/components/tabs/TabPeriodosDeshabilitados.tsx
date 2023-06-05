// 🖌️ AntDesign
import {
  DatePicker,
  Divider,
  Button,
  message,
  Popconfirm,
  Modal,
  notification,
  Tag,
  Table,
} from "antd";
import { PlusOutlined, DeleteFilled } from "@ant-design/icons";
import { RangePickerProps } from "antd/es/date-picker";
import { ColumnsType } from "antd/es/table";
//📅 necesarios para que funcione las fechas
import "dayjs/locale/es";
import locale from "antd/es/date-picker/locale/es_ES";
// 🌐 Librerias de terceros
import axios from "axios";
import { useState, useEffect } from "react";
// 😁 Componentes y funciones propias
import { useLugarStore } from "../../../../hooks/lugarStore";
//🖌️ AntDesign subcomponentes
const { RangePicker } = DatePicker;

interface TabPeriodosDeshabilitadosProps {
  lugarId: number;
}

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
];
export default function TabPeriodosDeshabilitados({
  lugarId,
}: TabPeriodosDeshabilitadosProps) {
  const [range, setRange] = useState<RangePickerProps["value"]>();
  const { estaGuardando, setGuardando } = useLugarStore();
  const [periodos, setPeriodos] = useState<PeriodoDeshabilitados[]>([]);
  useEffect(() => {
    getAllPeriodosDeshabilitados();
  }, []);
  const getAllPeriodosDeshabilitados = async () => {
    await axios
      .get(`/reservaciones/api/lugares/${lugarId}/periodosDeshabilitados`)
      .then((response) => {
        // console.log(response); //👀
        let data = response.data.map((item: PeriodoDeshabilitados) => ({
          key: `pd-${item.id}`,
          id: item.id,
          inicio: item.inicio,
          fin: item.fin,
        }));
        setPeriodos(data);
        setRange(undefined);
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: error.message,
          content: "No se pudo traer los periodos deshabilitados del lugar",
        });
      });
  };

  const handleChangeRangePicker = (values: RangePickerProps["value"]) => {
    setRange(values);
  };

  const confirm = () => {
    guardarPeriodoDeshabilitado();
  };

  const eliminarPeriodoDeshabilitado = async (periodoId: number) => {
    await axios
      .delete(
        `/reservaciones/api/lugares/${lugarId}/periodosDeshabilitados/${periodoId}`
      )
      .then(() => {
        // console.log(response); //👀 cambiar ".then((response) => {"
        notification.info({ message: "Se elimino el periodo" });
        getAllPeriodosDeshabilitados();
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: error.message,
          content: "Ocurrio un error al intentar eliminar el periodo",
        });
      });
  };
  const guardarPeriodoDeshabilitado = async () => {
    setGuardando(true);
    if (!!range) {
      if (!!range[0] && !!range[1]) {
        await axios
          .post(
            `/reservaciones/api/lugares/${lugarId}/periodosDeshabilitados`,
            {
              inicio: range[0].format("YYYY-MM-DD"),
              fin: range[1].format("YYYY-MM-DD"),
            }
          )
          .then(() => {
            // console.log(response); //👀 cambiar ".then((response) => {"
            notification.success({ message: "Periodo agregado" });
            getAllPeriodosDeshabilitados();
          })
          .catch((error) => {
            console.error(error);
            Modal.error({
              title: "Error al guardar",
              content: error.response.data.error.message,
            });
          });
      } else message.warning("Debe indicar el rango");
    } else message.warning("Debe indicar el rango");
    setGuardando(false);
  };

  return (
    <div className="gap-4 md:p-4 text-neutral-600">
      <h2 className="mb-6">Periodos deshabilitados</h2>
      <p>
        Agrega o quita periodos en el que lugar turístico estara cerrado las
        visitas de turístas
      </p>
      <form className="flex gap-2">
        <RangePicker
          value={range}
          onChange={handleChangeRangePicker}
          locale={locale}
          disabled={estaGuardando}
        />
        <Popconfirm
          title="Agregar periodo deshabilitado"
          description="¿Estas seguro de que quieres deshabilitar el lugar en este periodo?"
          onConfirm={confirm}
          okText="Si"
          cancelText="No"
          disabled={!range || estaGuardando}
        >
          <Button
            icon={<PlusOutlined />}
            type="primary"
            disabled={!range}
            loading={estaGuardando}
          ></Button>
        </Popconfirm>
      </form>
      <Divider className="col-span-5" />
      <Table
        columns={[
          ...colums,
          {
            title: "",
            className: "text-center",
            width: 100,
            render: (_, record) => {
              return (
                <Popconfirm
                  title="Eliminar periodo deshabilitado"
                  description="Al eliminar el periodo deshabilitado el lugar volvera a estar disponible para estas fechas. ¿Quieres eliminarlo?"
                  okText="Si"
                  cancelText="No"
                  overlayClassName="w-64"
                  onConfirm={() => eliminarPeriodoDeshabilitado(record.id)}
                >
                  <Tag
                    color="error"
                    icon={<DeleteFilled />}
                    className="cursor-pointer"
                  >
                    Eliminar
                  </Tag>
                </Popconfirm>
              );
            },
          },
        ]}
        dataSource={periodos}
        pagination={false}
        scroll={{ y: window.innerHeight - 430 }}
        size="middle"
      />
    </div>
  );
}
