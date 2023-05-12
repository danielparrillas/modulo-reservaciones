import { DatePicker, Divider, Button, message, Popconfirm } from "antd";
import { PlusOutlined } from "@ant-design/icons";
import { useState } from "react";
import { RangePickerProps } from "antd/es/date-picker";

import TableDisponibilidades from "../tables/TableDisponibilidades";

export default function TabLugarDisponibilidades() {
  return (
    <div className="gap-4 md:p-4 text-slate-600">
      <h2 className="text-center  mb-6">Disponibilidades</h2>
      <p>
        Gestiona los servicios de este lugar con cantidad disponible fija por
        dia
      </p>
      <form className="flex gap-2"></form>
      <Divider className="col-span-5" />
      <TableDisponibilidades />
    </div>
  );
}

//TabLugarDisponibilidades
