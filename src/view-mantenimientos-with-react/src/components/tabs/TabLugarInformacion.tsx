import { Input, Switch, Divider, Checkbox, Button } from "antd";
import { SaveFilled } from "@ant-design/icons";

export default function TabLugarInformacion() {
  const handleSubmit = (
    e:
      | React.FormEvent<HTMLFormElement>
      | React.MouseEvent<HTMLButtonElement, MouseEvent>
      | React.MouseEvent<HTMLAnchorElement, MouseEvent>
  ) => {
    e.preventDefault();
    console.log(e);
  };
  return (
    <form
      onSubmit={handleSubmit}
      className="grid grid-cols-5 gap-4 md:p-4 text-slate-600"
    >
      <h2 className="col-span-5 text-center mb-6">Nuevo lugar turístico</h2>
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Nombre</label>
        <p>Utilizado para identificar el lugar</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Input placeholder="nombre del lugar..." className="w-full" />
      </div>
      <Divider className="col-span-5" />
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Permite acampar</label>
        <p>Los turistas podrán quedarse mas de un día en el lugar</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Checkbox />
      </div>
      <Divider className="col-span-5" />
      <div className="col-span-2 lg:col-span-1">
        <label className="font-bold">Activo</label>
        <p>Permite recibir visitas de turistas</p>
      </div>
      <div className="col-span-3 lg:col-span-4">
        <Switch defaultChecked />
      </div>
      <div className="col-span-5 flex justify-end">
        <Button
          type="primary"
          icon={<SaveFilled />}
          size="large"
          onClick={handleSubmit}
        >
          Guardar
        </Button>
      </div>
    </form>
  );
}
