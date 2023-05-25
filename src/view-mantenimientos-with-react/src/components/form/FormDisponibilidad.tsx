// 🖌️ AntDesign
import { SaveFilled } from "@ant-design/icons";
import {
  Button,
  Popconfirm,
  Card,
  InputNumber,
  Modal,
  Collapse,
  notification,
} from "antd";
const { Panel } = Collapse;
// 🌐 Librerias de terceros
import axios from "axios";
import { useState } from "react";
// 😁 Componentes y funciones propias
import { useLugarStore } from "../../hooks/lugarStore";

interface FormDisponibilidadProps {
  id: number;
  nombre: string;
  lugarId: number;
  cantidadMaxima?: number;
}
export default function FormDisponibilidad({
  id,
  nombre,
  cantidadMaxima,
  lugarId,
}: FormDisponibilidadProps) {
  const [value, setValue] = useState(cantidadMaxima);
  const { modo, setModo } = useLugarStore();
  // console.log(id, nombre, cantidad, lugarId); //👀
  const handleConfirmSave = () => {
    setModo("guardando");
    guardar();
  };
  const guardar = async () => {
    await axios
      .put(`/reservaciones/app/api/lugares/${lugarId}/disponibilidades/${id}`, {
        cantidadMaxima: value,
      })
      .then(() => {
        // console.log(response); //👀 cambiar ".then((response) => {"
        notification.success({ message: `${nombre} disponibilidad guardada` });
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: error.message,
          content: (
            <Collapse>
              <Panel header={error.response.data.error.message} key={1}>
                {error.response.data.error.details.map(
                  (detail: string, index: number) => (
                    <p key={`detail-${index}`}>{detail}</p>
                  )
                )}
              </Panel>
            </Collapse>
          ),
        });
      })
      .finally(() => setModo("edicion"));
  };
  return (
    <Card title={nombre}>
      <div className="flex gap-4">
        <InputNumber
          size="large"
          min={0}
          className="w-60"
          placeholder="Digita un número"
          value={value}
          disabled={modo === "guardando"}
          onChange={(value) => setValue(!!value ? value : undefined)}
        />
        <Popconfirm
          title={"Confirmación"}
          description="¿Desea guardar esta disponibilidad?"
          onConfirm={() => handleConfirmSave()}
          okText="Si"
          cancelText="No"
          disabled={!value}
        >
          <Button
            type="primary"
            size="large"
            icon={<SaveFilled />}
            loading={modo === "guardando"}
            disabled={!value}
          >
            Guardar
          </Button>
        </Popconfirm>
      </div>
    </Card>
  );
}
