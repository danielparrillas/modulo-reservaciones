import { DatePicker, Divider, Button, message, Popconfirm } from "antd";
import { PlusOutlined } from "@ant-design/icons";
import { useState } from "react";
import { RangePickerProps } from "antd/es/date-picker";

import "dayjs/locale/es";
import locale from "antd/es/date-picker/locale/es_ES";

import TablePeriodosDeshabilitados from "../tables/TablePeriodosDeshabilitados";

const { RangePicker } = DatePicker;

export default function TabPeriodosDeshabilitados() {
  const [range, setRange] = useState<RangePickerProps["value"]>();

  const handleChangeRangePicker = (values: RangePickerProps["value"]) => {
    setRange(values);
  };

  const confirm = () => {
    message.info(range ? range[0]?.format("YYYY-MM-DD") : "No seleccionado");
    message.info(range ? range[1]?.format("YYYY-MM-DD") : "No seleccionado");
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
        />
        <Popconfirm
          title="Agregar periodo deshabilitado"
          description="¿Estas seguro de que quieres deshabilitar el lugar en este periodo?"
          onConfirm={confirm}
          onCancel={cancel}
          okText="Si"
          cancelText="No"
          disabled={!range}
        >
          <Button
            icon={<PlusOutlined />}
            type="primary"
            disabled={!range}
          ></Button>
        </Popconfirm>
      </form>
      <Divider className="col-span-5" />
      <TablePeriodosDeshabilitados />
    </div>
  );
}
