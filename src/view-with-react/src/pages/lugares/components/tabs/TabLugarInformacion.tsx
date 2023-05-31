import { useEffect, useState } from "react";
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
} from "antd";
import {
  SaveFilled,
  PlusOutlined,
  LoadingOutlined,
  EditFilled,
} from "@ant-design/icons";
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

interface TabLugarInformacionProps {
  lugarId?: number;
}

export default function TabLugarInformacion({
  lugarId,
}: TabLugarInformacionProps) {
  const { height } = useAppStore();
  const { tab, modo, setModo } = useLugarStore();
  const [lugar, setLugar] = useState<Lugar>({
    activo: true,
    permiteAcampar: false,
  });

  //Se mandara a llamar cada vez que se seleccione un lugar o se cambie de tab
  useEffect(() => {
    if (!!lugarId) getLugar(lugarId);
  }, [lugarId, tab]);

  const handleConfirmSave = () => {
    setModo("guardando");

    guardar();
  };
  const guardar = async () => {
    setModo("guardando");
    if (!!lugarId) {
      axios
        .put(`/reservaciones/app/services/lugares/${lugarId}`, {
          nombre: lugar.nombre,
          permiteAcampar: lugar.permiteAcampar,
          activo: lugar.activo,
        })
        .then(() => {
          // console.log(response); //👀 cambiar ".then((response) => {"
          notification.success({ message: "Lugar guardado" });
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
        .finally(() => setModo(!!lugarId ? "edicion" : "nuevo"));
    } else {
      axios
        .post("/reservaciones/app/services/lugares/", {
          nombre: lugar.nombre,
          permiteAcampar: lugar.permiteAcampar,
          activo: lugar.activo,
        })
        .then(() => {
          // console.log(response); //👀 cambiar a .then((response) => {
          // navigate(`/reservaciones/views/lugares/${response.data.data.id}`); //⚠️⚠️
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
        })
        .finally(() => setModo(!!lugarId ? "edicion" : "nuevo"));
    }
  };

  const getLugar = async (id: number) => {
    await axios
      .get(`/reservaciones/app/services/lugares/${id}`)
      .then((response) => {
        // console.log(response); //👀
        setLugar(response.data.data);
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Error al llamar los datos",
          content: error.message,
        });
      });
  };

  return (
    <form
      onSubmit={(e) => e.preventDefault()}
      className="text-slate-600 flex flex-col"
    >
      <div className="col-span-5 text-center p-4">
        {modo === "edicion" ? (
          <h2>
            <EditFilled /> Editar lugar turístico
          </h2>
        ) : modo === "nuevo" ? (
          <h2>
            <PlusOutlined /> Nuevo lugar turístico
          </h2>
        ) : (
          <h2 className="animate-pulse">
            <LoadingOutlined /> Guardando lugar turístico
          </h2>
        )}
      </div>
      <Divider className="m-0 pb-2" />
      <div
        className="grid grid-cols-5 overflow-auto px-4"
        style={{ height: height - 300 }}
      >
        <div className="col-span-2">
          <label className="font-bold">Nombre</label>
          <p>Utilizado para identificar el lugar</p>
        </div>
        <div className="col-span-3 flex items-center">
          <Input
            placeholder="nombre del lugar..."
            className="w-full"
            disabled={modo === "guardando"}
            value={lugar.nombre}
            onChange={(e) => setLugar({ ...lugar, nombre: e.target.value })}
            required
          />
        </div>
        <div className="col-span-2">
          <label className="font-bold">ANP</label>
          <p>Área protegida en la que se encuentra</p>
        </div>
        <div className="col-span-3 flex items-center">
          <SelectAnp />
        </div>
        <div className="col-span-2">
          <label className="font-bold">Descripción</label>
          <p></p>
        </div>
        <div className="col-span-3 flex items-center">
          <TextArea maxLength={500} showCount className="mb-7 w-full" />
        </div>
        <div className="col-span-2">
          <label className="font-bold">Permite acampar</label>
          <p>Los turistas podrán quedarse mas de un día en el lugar</p>
        </div>
        <div className="col-span-3 flex items-center">
          <Checkbox
            disabled={modo === "guardando"}
            checked={lugar.permiteAcampar}
            onChange={(e) =>
              setLugar({ ...lugar, permiteAcampar: e.target.checked })
            }
          />
        </div>
        <div className="col-span-2">
          <label className="font-bold">Activo</label>
          <p>Permite recibir visitas de turistas</p>
        </div>
        <div className="col-span-3 flex items-center">
          <Switch
            defaultChecked
            disabled={modo === "guardando"}
            checked={lugar.activo}
            onChange={(checked) => setLugar({ ...lugar, activo: checked })}
          />
        </div>
      </div>
      <div className="col-span-5 flex justify-end">
        <Popconfirm
          title={"Confirmación"}
          description={
            modo === "edicion"
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
            loading={modo === "guardando"}
            disabled={!lugar.nombre?.trim()}
          >
            {modo === "guardando"
              ? "Guardando"
              : modo === "edicion"
              ? "Guardar"
              : "Crear"}
          </Button>
        </Popconfirm>
      </div>
    </form>
  );
}
