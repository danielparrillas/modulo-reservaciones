// 🖌️ AntDesign
import {
  Modal,
  Input,
  InputNumber,
  Switch,
  message,
  notification,
  Divider,
  Popconfirm,
  Button,
} from "antd";
import TextArea from "antd/es/input/TextArea";
import { SaveFilled } from "@ant-design/icons";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import SelectDisponibilidad from "./SelectDisponibilidad";
import { useServicioStore } from "../../../hooks/servicioStore";
import { useAppStore } from "../../../hooks/appStore";

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

    setDisponibilidadGrupoId,
    disponibilidadGrupoId,
    estaGuardando,
    setGuardando,
  } = useServicioStore();
  const [servicio, setServicio] = useState<Servicio>({ eliminado: false });
  const { height, setVista } = useAppStore();

  useEffect(() => {
    if (!!servicioSeleccionadoId) {
      getServicio(servicioSeleccionadoId);
    }
  }, [servicioSeleccionadoId]);

  //🔃 comunicacion con backend
  const getServicio = async (id: number) => {
    await axios
      .get(`/reservaciones/api/servicios/${id}`)
      .then((response) => {
        // console.log(response); //👀
        setDisponibilidadGrupoId(response.data.disponibilidadId);
        setServicio(response.data);
      })
      .catch((error) => {
        Modal.error({
          title: "Ocurrio un error al llamar el servicio",
          content: error.message,
        });
        console.error(error);
      });
  };
  const createServicio = async () => {
    setGuardando(true);
    axios
      .put("/reservaciones/api/servicios", {
        nombre: servicio.nombre,
        descripcion: servicio.descripcion,
        disponibilidadId: disponibilidadGrupoId,
        precio: servicio.precio,
        eliminado: servicio.eliminado,
      })
      .then(({ data }) => {
        console.log(data); //👀
        if (!!data.id) {
          notification.success({
            message: `Nuevo servicio creado [${data.id}]`,
          });
          // reiniciamos datos
          setDisponibilidadGrupoId();
          setServicio({ eliminado: false });
          setServicioSeleccionadoId();
          setVista("lista");
        } else {
          Modal.error({
            title: "Ocurrio un error al crear un nuevo servicio",
            content: "No se obtuvo la respuesta esperada",
          });
        }
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Ocurrio un error al crear un nuevo servicio",
          content: error.message,
        });
      });
    setGuardando(false);
  };
  const updatedServicio = async () => {
    setGuardando(true);
    await axios
      .patch(`/reservaciones/api/servicios/${servicioSeleccionadoId}`, {
        nombre: servicio.nombre,
        descripcion: servicio.descripcion,
        disponibilidadId: disponibilidadGrupoId,
        precio: servicio.precio,
        eliminado: servicio.eliminado,
      })
      .then(() => {
        notification.success({ message: "Servicio guardado" });
        // reiniciamos datos
        setDisponibilidadGrupoId();
        setServicio({ eliminado: false });
        setServicioSeleccionadoId();
        setVista("lista");
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Ocurrio un error al actualizar",
          content: error.message,
        });
      });
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
    if (servicio.precio === undefined || servicio.precio === null) {
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

  return (
    <form
      onSubmit={(e) => e.preventDefault()}
      className="text-neutral-600 flex flex-col gap-2"
    >
      <div className="col-span-5 p-4 flex gap-4">
        {!!servicioSeleccionadoId ? (
          <h3>Editar servicio</h3>
        ) : !servicioSeleccionadoId ? (
          <h3>Nuevo lugar turístico</h3>
        ) : (
          <h3 className="animate-pulse">Guardando lugar turístico</h3>
        )}
      </div>
      <Divider className="m-0 p-0" />
      <div
        className="flex flex-col md:grid md:grid-cols-5 overflow-auto px-4 gap-2"
        style={{ height: height - 330 }}
      >
        <div className="col-span-1 flex items-center">
          <label>Servicio</label>
        </div>
        <div className="col-span-4 flex items-center">
          <Input
            value={servicio?.nombre}
            onChange={({ target }) =>
              setServicio({ ...servicio, nombre: target.value })
            }
            disabled={estaGuardando}
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label>Descripción</label>
        </div>
        <div className="col-span-4 flex items-center">
          <TextArea
            value={servicio?.descripcion}
            onChange={({ target }) =>
              setServicio({ ...servicio, descripcion: target.value })
            }
            maxLength={500}
            disabled={estaGuardando}
            showCount
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label className="w-36">Precio</label>
        </div>
        <div className="col-span-4 flex items-center">
          <InputNumber
            value={servicio?.precio}
            onChange={(value) =>
              setServicio({
                ...servicio,
                precio:
                  value !== null ? Math.abs(Number(value.toFixed(2))) : null,
              })
            }
            disabled={estaGuardando}
            addonBefore="$"
            className="w-72"
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label className="w-36">Disponibilidad</label>
        </div>
        <div className="col-span-4 flex items-center">
          <SelectDisponibilidad
            onSelect={setDisponibilidadGrupoId}
            idDisponibilidad={disponibilidadGrupoId}
            disabled={estaGuardando}
            className="w-72"
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label className="w-36">Activo</label>
        </div>
        <div className="col-span-4 flex items-center">
          <Switch
            checked={!servicio?.eliminado}
            onChange={(value) =>
              setServicio({ ...servicio, eliminado: !value })
            }
            disabled={estaGuardando}
          />
        </div>
      </div>
      <div className="col-span-5 flex justify-end">
        <Popconfirm
          title={"Confirmación"}
          description={
            !!servicioSeleccionadoId
              ? "¿Desea guardar los nuevos cambios para este servicio?"
              : "¿Desea crear un nuevo servicio?"
          }
          onConfirm={handleOk}
          okText="Si"
          cancelText="No"
          disabled={estaGuardando}
        >
          <Button
            type="primary"
            icon={<SaveFilled />}
            size="large"
            loading={estaGuardando}
          >
            {estaGuardando
              ? "Guardando"
              : !!servicioSeleccionadoId
              ? "Guardar"
              : "Crear"}
          </Button>
        </Popconfirm>
      </div>
    </form>
  );
}
