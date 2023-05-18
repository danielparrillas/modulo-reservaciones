// 🖌️ AntDesign
import { Button, Popconfirm, Card, InputNumber } from "antd";
import { SaveFilled } from "@ant-design/icons";
// 🌐 Librerias de terceros
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
  const { setModo } = useLugarStore();
  // console.log(id, nombre, cantidad, lugarId); //👀
  return (
    <Card title={nombre}>
      <div className="flex gap-4">
        <InputNumber
          size="large"
          min={0}
          className="w-60"
          placeholder="Digita un número"
          value={cantidadMaxima}
        />
        <Popconfirm
          title={"Confirmación"}
          description="¿Desea guardar esta disponibilidad?"
          onConfirm={() => {
            setModo("guardando");
          }}
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
