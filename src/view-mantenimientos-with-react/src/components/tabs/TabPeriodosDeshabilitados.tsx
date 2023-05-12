import { DatePicker, Button } from "antd";
import { PlusOutlined } from "@ant-design/icons";

const { RangePicker } = DatePicker;

export default function TabPeriodosDeshabilitados() {
  return (
    <div className="gap-4 p-4 text-slate-600">
      <h2 className="text-center">Periodos deshabilitados</h2>
      <form className="flex gap-2">
        <RangePicker />
        <Button icon={<PlusOutlined />} type="primary"></Button>
      </form>
    </div>
  );
}
