// 🖌️ AntDesign
import { DatePicker, Divider, Button, message, Popconfirm, Modal } from "antd";
import { PlusOutlined } from "@ant-design/icons";
import { RangePickerProps } from "antd/es/date-picker";
//📅 necesarios para que funcione las fechas
import "dayjs/locale/es";
import locale from "antd/es/date-picker/locale/es_ES";
// 🌐 Librerias de terceros
import axios from "axios";
import { useState } from "react";
// 😁 Componentes y funciones propias
import TablePeriodosDeshabilitados from "../tables/TablePeriodosDeshabilitados";
import { useLugarStore } from "../../hooks/lugarStore";
//🖌️ AntDesign subcomponentes
const { RangePicker } = DatePicker;

interface TabPeriodosDeshabilitadosProps {
  lugarId: number;
}
export default function TabPeriodosDeshabilitados({
  lugarId,
}: TabPeriodosDeshabilitadosProps) {
  const [range, setRange] = useState<RangePickerProps["value"]>();
  const { modo, setModo } = useLugarStore();

  const handleChangeRangePicker = (values: RangePickerProps["value"]) => {
    setRange(values);
  };

  const confirm = () => {
    guardarPeriodoDeshabilitado();
  };

  const guardarPeriodoDeshabilitado = async () => {
    setModo("guardando");
    if (!!range) {
      if (!!range[0] && !!range[1]) {
        await axios
          .post(
            `/reservaciones/app/api/lugares/${lugarId}/periodosDeshabilitados`,
            {
              inicio: range[0].format("YYYY-MM-DD"),
              fin: range[1].format("YYYY-MM-DD"),
            }
          )
          .then((response) => {
            console.log(response); //👀
          })
          .catch((error) => {
            console.error(error);
            Modal.error({
              title: "Error al guardar",
              content: error.message,
            });
          });
      } else message.warning("Debe indicar el rango");
    } else message.warning("Debe indicar el rango");
    setModo("edicion");
  };
  const cancel = () => {
    // setRange(undefined);
  };

  return (
    <div className="gap-4 md:p-4 text-slate-600">
      <h2 className="text-center  mb-6">Periodos deshabilitados</h2>
      <p>
        Agrega o quita periodos en el que lugar turístico estara cerrado para
        las visitas de turístas
      </p>
      <form className="flex gap-2">
        <RangePicker
          value={range}
          onChange={handleChangeRangePicker}
          locale={locale}
          disabled={modo === "guardando"}
        />
        <Popconfirm
          title="Agregar periodo deshabilitado"
          description="¿Estas seguro de que quieres deshabilitar el lugar en este periodo?"
          onConfirm={confirm}
          onCancel={cancel}
          okText="Si"
          cancelText="No"
          disabled={!range || modo === "guardando"}
        >
          <Button
            icon={<PlusOutlined />}
            type="primary"
            disabled={!range}
            loading={modo === "guardando"}
          ></Button>
        </Popconfirm>
      </form>
      <Divider className="col-span-5" />
      <TablePeriodosDeshabilitados lugarId={lugarId} />
    </div>
  );
}
