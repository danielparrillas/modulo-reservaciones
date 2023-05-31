// 🖌️ AntDesign
import { SaveFilled, UndoOutlined } from "@ant-design/icons";
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
import { useEffect, useState } from "react";
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
  const [value, setValue] = useState<null | undefined | number>(cantidadMaxima);
  const { tab, estaGuardando, setGuardando } = useLugarStore();
  // console.log(id, nombre, cantidad, lugarId); //👀
  useEffect(() => {}, [tab]);
  const handleConfirmSave = () => {
    setGuardando(true);
    guardar();
  };
  const guardar = async () => {
    await axios
      .put(
        `/reservaciones/app/services/lugares/${lugarId}/disponibilidades/${id}`,
        {
          cantidadMaxima: value,
        }
      )
      .then(() => {
        // console.log(response); //👀 cambiar ".then((response) => {"
        notification.success({ message: `${nombre} disponibilidad guardada` });
        setGuardando(false);
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
      });
  };
  return (
    <Card title={nombre} className="h-min">
      <div className="flex flex-wrap gap-4">
        <InputNumber
          size="large"
          min={0}
          className="w-60"
          placeholder="Digita un número"
          value={value}
          disabled={estaGuardando}
          onChange={(value) => setValue(value)}
        />
        <Popconfirm
          title={"Confirmación"}
          description="¿Desea guardar esta disponibilidad?"
          onConfirm={() => handleConfirmSave()}
          okText="Si"
          cancelText="No"
          disabled={
            value === undefined || value === null || value === cantidadMaxima
          }
        >
          <Button
            type="primary"
            size="large"
            icon={<SaveFilled />}
            loading={estaGuardando}
            disabled={
              value === undefined || value === null || value === cantidadMaxima
            }
          >
            Guardar
          </Button>
        </Popconfirm>
        <Button
          icon={<UndoOutlined />}
          size="large"
          onClick={() => setValue(cantidadMaxima)}
          disabled={value === cantidadMaxima}
        />
      </div>
    </Card>
  );
}
