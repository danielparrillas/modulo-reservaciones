// 🖌️ AntDesign
import { Select } from "antd";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias

interface Disponibilidad {
  id: number;
  nombre: string;
}

interface SelectProps {
  idDisponibilidad?: number;
  className?: string;
  disabled?: boolean;
  onSelect: (id: number) => void;
}
export default function SelectDisponibilidad({
  idDisponibilidad,
  className,
  disabled,
  onSelect,
}: SelectProps) {
  const [options, setOptions] = useState<Disponibilidad[]>([]);
  useEffect(() => {
    getDisponibilidadGrupos();
  }, []);

  const getDisponibilidadGrupos = async () => {
    await axios
      .get("../reservaciones/api/disponibilidades")
      .then((response) => {
        // console.log(response); //👀
        setOptions(response.data);
      })
      .catch((error) => {
        console.error(error);
      });
  };

  return (
    <Select
      value={idDisponibilidad}
      options={options.map((item) => ({
        id: item.id,
        value: item.id,
        label: item.nombre,
      }))}
      onChange={(value) => {
        onSelect(value);
      }}
      className={className}
      disabled={disabled}
    />
  );
}
