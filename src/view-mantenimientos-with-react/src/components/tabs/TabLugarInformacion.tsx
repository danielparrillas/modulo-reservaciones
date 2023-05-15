import { Input, Switch, Divider, Checkbox, Button, Popconfirm } from "antd";
import {
  SaveFilled,
  PlusOutlined,
  LoadingOutlined,
  EditFilled,
} from "@ant-design/icons";
import { useLugarStore } from "../../hooks/lugarStore";

export default function TabLugarInformacion() {
  const { modo, setModo } = useLugarStore();
  const handleConfirmSave = () => {
    setModo("guardando");
  };
  return (
    <form
      onSubmit={(e) => e.preventDefault()}
      className="grid grid-cols-5 gap-4 p-4 text-slate-600"
    >
      <div className="col-span-5 text-center mb-6">
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
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Nombre</label>
        <p>Utilizado para identificar el lugar</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Input
          placeholder="nombre del lugar..."
          className="w-full"
          disabled={modo === "guardando"}
          required
        />
      </div>
      <Divider className="col-span-5" />
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Permite acampar</label>
        <p>Los turistas podrán quedarse mas de un día en el lugar</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Checkbox disabled={modo === "guardando"} />
      </div>
      <Divider className="col-span-5" />
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Activo</label>
        <p>Permite recibir visitas de turistas</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Switch defaultChecked disabled={modo === "guardando"} />
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
        >
          <Button
            type="primary"
            icon={<SaveFilled />}
            size="large"
            loading={modo === "guardando"}
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
