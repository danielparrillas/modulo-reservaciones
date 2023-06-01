import { useEffect, useState, useRef } from "react";
// 🖌️ AntDesign
import {
  Input,
  Switch,
  Divider,
  Checkbox,
  Button,
  Popconfirm,
  Modal,
  Collapse,
  notification,
  Tooltip,
  Tour,
} from "antd";
import type { TourProps } from "antd";
import { SaveFilled, UndoOutlined, QuestionOutlined } from "@ant-design/icons";
const { Panel } = Collapse;
// 🌐 Librerias de terceros
import axios from "axios";
// 😁 Componentes y funciones propias
import { useLugarStore } from "../../hooks/lugarStore";
import SelectAnp from "../form/SelectAnp";
import { useAppStore } from "../../hooks/appStore";
import TextArea from "antd/es/input/TextArea";

interface Lugar {
  id?: number;
  nombre?: string;
  permiteAcampar?: boolean;
  activo?: boolean;
}

export default function TabLugarInformacion() {
  const { height } = useAppStore();
  const {
    lugarSeleccionado,
    estaGuardando,
    setGuardando,
    setLugarSeleccionado,
  } = useLugarStore();
  const [lugar, setLugar] = useState<Lugar>({
    activo: true,
    permiteAcampar: false,
  });
  const [lugarCurrent, setLugarCurrent] = useState<Lugar>(lugar);
  //Se mandara a llamar cada vez que se seleccione un lugar o se cambie de tab
  useEffect(() => {
    if (!!lugarSeleccionado) {
      getLugar(lugarSeleccionado);
    }
  }, [lugarSeleccionado]);

  const handleConfirmSave = () => {
    // setGuardando(true);
    guardar();
  };
  const guardar = async () => {
    if (!!lugarSeleccionado) {
      axios
        .put(`/reservaciones/api/lugares/${lugarSeleccionado}`, {
          nombre: lugar.nombre,
          permiteAcampar: lugar.permiteAcampar,
          activo: lugar.activo,
        })
        .then(() => {
          // console.log(response); //👀 cambiar ".then((response) => {"
          notification.success({ message: "Lugar guardado" });
          setGuardando(false);
          setLugarCurrent(lugar);
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
    } else {
      axios
        .post("/reservaciones/api/lugares/", {
          nombre: lugar.nombre,
          permiteAcampar: lugar.permiteAcampar,
          activo: lugar.activo,
        })
        .then((response) => {
          console.log(response.data.id); //👀 cambiar a .then((response) => {
          setGuardando(false);
          setLugarSeleccionado(response.data.id);
          setLugarCurrent(lugar);
          Modal.success({ title: "Nuevo lugar creado" });
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
    }
  };

  const getLugar = async (id: number) => {
    await axios
      .get(`/reservaciones/api/lugares/${id}`)
      .then((response) => {
        // console.log(response); //👀
        setLugar(response.data.data);
        setLugarCurrent(response.data.data);
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Error al llamar los datos",
          content: error.message,
        });
      });
  };

  //😎 tour
  const ref1 = useRef(null);
  const ref2 = useRef(null);
  const ref3 = useRef(null);
  const ref4 = useRef(null);
  const ref5 = useRef(null);
  const ref6 = useRef(null);
  const [openTour, setOpenTour] = useState<boolean>(false);
  const steps: TourProps["steps"] = [
    {
      title: "Escribe el nombre del lugar turistico",
      description:
        "Utilizado para identificar el lugar. Posiblemente tenga el mismo nombre que la ANP a la que pertenece.",
      target: () => ref1.current,
    },
    {
      title: "Selecciona la ANP",
      description:
        "Puedes escribir para filtrar la Área protegida en la que se encuentra el lugar.",
      target: () => ref2.current,
    },
    {
      title: "Agrega una descripción",
      description: "Este es un campo abierto.",
      target: () => ref3.current,
    },
    {
      title: "Marca si se puede acampar",
      description:
        "Esto indica a los turistas si pueden quedarse mas de un día en el lugar.",
      target: () => ref4.current,
    },
    {
      title: "Indica si el lugar esta disponible a visitas",
      description:
        "Puedes activar o desactivar para indicarle al turista si este lugar esta abierto a visitas.",
      target: () => ref5.current,
    },
    {
      title: "Guarda los cambios",
      target: () => ref6.current,
    },
  ];

  return (
    <form
      onSubmit={(e) => e.preventDefault()}
      className="text-neutral-600 flex flex-col"
    >
      <div className="col-span-5 p-4 flex gap-4">
        {!!lugarSeleccionado ? (
          <h2>Editar lugar turístico</h2>
        ) : !lugarSeleccionado ? (
          <h2>Nuevo lugar turístico</h2>
        ) : (
          <h2 className="animate-pulse">Guardando lugar turístico</h2>
        )}
        <Tooltip title={"Restablecer cambios"}>
          <Button
            icon={<UndoOutlined />}
            onClick={() => setLugar(lugarCurrent)}
            // disabled={value === currentValue}
          />
        </Tooltip>
        <Tooltip title={"Realizar tour de ayuda"}>
          <Button
            icon={<QuestionOutlined />}
            onClick={() => setOpenTour(true)}
            // onClick={() => setValue(currentValue)}
            // disabled={value === currentValue}
          />
        </Tooltip>
      </div>
      <Divider className="m-0 pb-2" />
      <div
        className="grid grid-cols-5 overflow-auto px-4 gap-2"
        style={{ height: height - 330 }}
      >
        <div className="col-span-1 flex items-center">
          <label>Nombre</label>
        </div>
        <div className="col-span-4 flex items-center" ref={ref1}>
          <Input
            placeholder="nombre del lugar..."
            className="w-full"
            disabled={estaGuardando}
            value={lugar.nombre}
            onChange={(e) => setLugar({ ...lugar, nombre: e.target.value })}
            required
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label>ANP</label>
        </div>
        <div className="col-span-4 flex items-center" ref={ref2}>
          <SelectAnp />
        </div>
        <div className="col-span-1 flex items-center">
          <label>Descripción</label>
          <p></p>
        </div>
        <div className="col-span-4 flex items-center" ref={ref3}>
          <TextArea maxLength={500} showCount className="mb-7 w-full" />
        </div>
        <div className="col-span-1 flex items-center">
          <label>Permite acampar</label>
        </div>
        <div className="col-span-4 flex items-center" ref={ref4}>
          <Checkbox
            disabled={estaGuardando}
            checked={lugar.permiteAcampar}
            onChange={(e) =>
              setLugar({ ...lugar, permiteAcampar: e.target.checked })
            }
          />
        </div>
        <div className="col-span-1 flex items-center">
          <label>Activo</label>
        </div>
        <div className="col-span-4 flex items-center" ref={ref5}>
          <Switch
            defaultChecked
            disabled={estaGuardando}
            checked={lugar.activo}
            onChange={(checked) => setLugar({ ...lugar, activo: checked })}
          />
        </div>
      </div>
      <div className="col-span-5 flex justify-end" ref={ref6}>
        <Popconfirm
          title={"Confirmación"}
          description={
            !!lugarSeleccionado
              ? "¿Desea guardar los nuevos cambios para este lugar?"
              : "¿Desea crear un nuevo lugar?"
          }
          onConfirm={handleConfirmSave}
          okText="Si"
          cancelText="No"
          disabled={!lugar.nombre?.trim()}
        >
          <Button
            type="primary"
            icon={<SaveFilled />}
            size="large"
            loading={estaGuardando}
            disabled={!lugar.nombre?.trim() || lugarCurrent === lugar}
          >
            {estaGuardando
              ? "Guardando"
              : !!lugarSeleccionado
              ? "Guardar"
              : "Crear"}
          </Button>
        </Popconfirm>
      </div>
      <Tour open={openTour} onClose={() => setOpenTour(false)} steps={steps} />
    </form>
  );
}
