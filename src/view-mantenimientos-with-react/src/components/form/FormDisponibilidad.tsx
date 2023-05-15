import { Button, Popconfirm, Card, InputNumber } from "antd";
import { SaveFilled } from "@ant-design/icons";

interface FormDisponibilidadProps {
  id: number;
  nombre: string;
  cantidad?: number;
}
export default function FormDisponibilidad({
  id,
  nombre,
  cantidad,
}: FormDisponibilidadProps) {
  return (
    <Card title={nombre + " " + id}>
      <div className="flex gap-4">
        <InputNumber
          size="large"
          min={0}
          className="w-60"
          placeholder="Digita un número"
          value={cantidad}
        />
        <Popconfirm
          title={"Confirmación"}
          description="¿Desea guardar esta disponibilidad?"
          onConfirm={() => {}}
          okText="Si"
          cancelText="No"
        >
          <Button type="primary" size="large" icon={<SaveFilled />}>
            Guardar
          </Button>
        </Popconfirm>
      </div>
    </Card>
  );
}
