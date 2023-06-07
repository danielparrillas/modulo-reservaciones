// 🖌️ AntDesign
import { Divider } from "antd";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import FormDisponibilidad from "../form/FormDisponibilidad";
import { useLugarStore } from "../../../../hooks/lugarStore";

interface Disponibilidad {
  id: number;
  nombre: string;
  lugarId: number;
  cantidadMaxima?: number;
}

interface TabLugarDisponibilidadesProps {
  lugarId: number;
}
export default function TabLugarDisponibilidades({
  lugarId,
}: TabLugarDisponibilidadesProps) {
  const [disponibilidades, setDisponibilidades] = useState<Disponibilidad[]>(
    []
  );
  const { tab, nombre: nombreLugar } = useLugarStore();

  useEffect(() => {
    if (!!lugarId) transformarData();
  }, [lugarId]);

  useEffect(() => {
    transformarData();
  }, [tab]);

  const transformarData = async () => {
    const disponibilidades = await getDisponibilidades();
    const disponibilidadesDelLugar = await getDisponibilidadesDelLugar();

    const resultado: Disponibilidad[] = disponibilidades.map((disp) => {
      const disLu = disponibilidadesDelLugar.find(
        (item) => disp.id === item.grupoId
      );
      // console.log(disLu); //👀
      return {
        id: disp.id,
        lugarId: lugarId,
        nombre: disp.nombre,
        cantidadMaxima: !!disLu ? disLu.cantidadMaxima : undefined,
      };
    });
    // console.log(resultado); //👀
    setDisponibilidades(resultado);
  };

  const getDisponibilidades = async () => {
    let disponibilidades: any[] = [];
    await axios
      .get("/reservaciones/api/disponibilidades")
      .then(async (response) => {
        // console.log(response); //👀
        disponibilidades = response.data;
      })
      .catch((error) => {
        console.error(error);
      });
    return disponibilidades;
  };

  const getDisponibilidadesDelLugar = async () => {
    let disponibilidadesDelLugar: any[] = [];
    await axios
      .get(`/reservaciones/api/lugares/${lugarId}/disponibilidades`)
      .then((response) => {
        // console.log(response); //👀
        disponibilidadesDelLugar = response.data;
      })
      .catch((error) => {
        console.error(error);
      });
    return disponibilidadesDelLugar;
  };

  return (
    <div className="gap-4 md:p-4 text-neutral-600">
      <h3 className="mb-6">Disponibilidades de {nombreLugar}</h3>
      <p>
        Indica la cantidad maxima diaria de estos servicios. Esta cantidad será
        utilizada en el sistema para validar las reservaciones.
      </p>
      <form className="flex gap-2"></form>
      <Divider className="col-span-5" />
      <section
        className="grid grid-cols-1 xl:grid-cols-2 gap-4 overflow-auto"
        style={{ height: window.innerHeight - 400 }}
      >
        {disponibilidades.map((grupo) => (
          <FormDisponibilidad
            key={`grupo-servicio-${grupo.id}`}
            id={grupo.id}
            nombre={grupo.nombre}
            cantidadMaxima={grupo.cantidadMaxima}
            lugarId={lugarId}
          />
        ))}
      </section>
    </div>
  );
}
