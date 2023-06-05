// 🖌️ AntDesign
import { Modal, Input, InputNumber, Switch, message } from "antd";
import TextArea from "antd/es/input/TextArea";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import SelectDisponibilidad from "./SelectDisponibilidad";
import { useServicioStore } from "../../../hooks/servicioStore";

interface Servicio {
  descripcion?: string;
  disponibilidadId?: number;
  eliminado?: boolean;
  id?: number;
  nombre?: string;
  precio?: number | null;
}

export default function ServicioForm() {
  const {
    servicioSeleccionadoId,
    setServicioSeleccionadoId,
    isOpenForm,
    setIsOpenForm,
    setDisponibilidadGrupoId,
    disponibilidadGrupoId,
    estaGuardando,
    setGuardando,
  } = useServicioStore();
  const [servicio, setServicio] = useState<Servicio>({ eliminado: false });

  useEffect(() => {
    if (!!servicioSeleccionadoId) {
      getServicio();
    }
  }, [servicioSeleccionadoId]);

  //🔃 comunicacion con backend
  const getServicio = async () => {
    await axios
      .get(`/reservaciones/api/servicios/${servicioSeleccionadoId}`)
      .then((response) => {
        // console.log(response); //👀
        setDisponibilidadGrupoId(response.data.disponibilidadId);
        setServicio(response.data);
      })
      .catch((error) => {
        setIsOpenForm(false);
        Modal.error({
          title: "Ocurrio un error al llamar el servicio",
          content: error.message,
        });
        console.error(error);
      });
  };

  const createServicio = () => {
    setGuardando(true);
    //setIsOpenForm(false);
    // setServicio({ eliminado: false }); //reiniciamos el servicio
    console.log("creando");
    setGuardando(false);
  };
  const updatedServicio = () => {
    setGuardando(true);
    //setIsOpenForm(false);
    console.log("actualizando");
    // setServicio({ eliminado: false }); //reiniciamos el servicio
    setGuardando(false);
  };
  // 🖐🏻 handlers
  const handleOk = () => {
    // console.log(servicio); //👀
    let ok = true;
    //📝 validacion nombre
    if (!servicio?.nombre) {
      message.error({ content: "Debe especificar el nombre del servicio" });
      ok = false;
    } else {
      if (!servicio.nombre.trim()) {
        message.error({
          content: "El nombre del servicio no debe ser una cadena vacía",
        });
        ok = false;
      }
    }
    //📝 validacion de descripcion
    if (!servicio?.descripcion) {
      message.error({ content: "Debe poner una descripción" });
      ok = false;
    } else {
      if (!servicio.descripcion.trim()) {
        message.error({
          content: "La descripción del no debe ser una cadena vacía",
        });
        ok = false;
      }
    }
    //📝 validacion del precio
    if (!servicio?.precio) {
      message.error({ content: "Debe poner un precio" });
      ok = false;
    }
    //📝 validacion del disponibilidadId
    if (!disponibilidadGrupoId) {
      message.error({ content: "Debe seleccionar una disponibilidad" });
      ok = false;
    }

    //⏺️
    if (ok) {
      if (!servicioSeleccionadoId) createServicio();
      else updatedServicio();
    }
  };

  const handleCancel = () => {
    setIsOpenForm(false);
    setServicio({ eliminado: false }); //reiniciamos el servicio
    setServicioSeleccionadoId(undefined);
  };
  return (
    <Modal
      title={!!servicioSeleccionadoId ? "Editar servicio" : "Nuevo servicio"}
      open={isOpenForm}
      onOk={handleOk}
      onCancel={handleCancel}
      okText="Guardar"
      cancelText="Cancelar"
      closable={!estaGuardando}
      okButtonProps={{ loading: estaGuardando }}
      cancelButtonProps={{ disabled: estaGuardando }}
      maskClosable={false}
    >
      <form
        onSubmit={(e) => e.preventDefault()}
        className="grid grid-cols-1 gap-4 text-neutral-600"
      >
        <label className="font-normal">Servicio</label>
        <Input
          value={servicio?.nombre}
          onChange={({ target }) =>
            setServicio({ ...servicio, nombre: target.value })
          }
          disabled={estaGuardando}
        />
        <label className="font-normal">Descripción</label>
        <TextArea
          value={servicio?.descripcion}
          onChange={({ target }) =>
            setServicio({ ...servicio, descripcion: target.value })
          }
          maxLength={500}
          disabled={estaGuardando}
          showCount
        />
        <div className="flex flex-col md:flex-row gap-6 pb-4">
          <label className="font-normal w-36">Precio</label>
          <InputNumber
            value={servicio?.precio}
            onChange={(value) =>
              setServicio({
                ...servicio,
                precio: !!value ? Math.abs(Number(value.toFixed(2))) : null,
              })
            }
            disabled={estaGuardando}
            addonBefore="$"
            className="w-72"
          />
        </div>
        <div className="flex flex-col md:flex-row gap-6 pb-4">
          <label className="font-normal w-36">Disponibilidad</label>
          <SelectDisponibilidad
            onSelect={setDisponibilidadGrupoId}
            idDisponibilidad={disponibilidadGrupoId}
            disabled={estaGuardando}
            className="w-72"
          />
        </div>
        <div className="flex gap-6 pb-4">
          <label className="font-normal w-36">Activo</label>
          <Switch
            checked={!servicio?.eliminado}
            onChange={(value) =>
              setServicio({ ...servicio, eliminado: !value })
            }
            disabled={estaGuardando}
          />
        </div>
      </form>
    </Modal>
  );
}
