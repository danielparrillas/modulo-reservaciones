import { Input } from "antd";

export default function TabLugarInformacion() {
  return (
    <div className="flex flex-col justify-center text-center md:px-4">
      <h2>Nuevo lugar</h2>
      <div className="flex justify-center items-center gap-2 md:gap-6">
        <label htmlFor="">Nombre</label>
        <Input placeholder="Basic usage" />
      </div>
    </div>
  );
}
