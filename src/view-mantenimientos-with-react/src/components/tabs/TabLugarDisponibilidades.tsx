import { Divider, Button, Popconfirm, Card, InputNumber } from "antd";
import { SaveFilled } from "@ant-design/icons";

import FormDisponibilidad from "../form/FormDisponibilidad";

const gruposServicios = [
  { id: 1, nombre: "Entradas", cantidad: 123 },
  { id: 2, nombre: "Parqueos" },
];

export default function TabLugarDisponibilidades() {
  return (
    <div className="gap-4 md:p-4 text-slate-600">
      <h2 className="text-center  mb-6">Disponibilidades</h2>
      <p>
        Indica la cantidad maxima diaria de estos servicios. Esta cantidad será
        utilizada en el sistema para validar las reservaciones.
      </p>
      <form className="flex gap-2"></form>
      <Divider className="col-span-5" />
      <section className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {gruposServicios.map((grupo) => (
          <FormDisponibilidad
            key={`grupo-servicio-${grupo.id}`}
            id={grupo.id}
            nombre={grupo.nombre}
            cantidad={grupo.cantidad}
          />
        ))}
      </section>
    </div>
  );
}

//TabLugarDisponibilidades
