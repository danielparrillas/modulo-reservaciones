import { useEffect, useState } from "react";
import { Dayjs } from "dayjs";
import { useAppStore } from "../../hooks/appStore";
import { UnorderedListOutlined, SaveFilled } from "@ant-design/icons";
import { DatePicker, message, Space, Button } from "antd";

export default function FormLugar() {
  const [date, setDate] = useState<Dayjs | null>(null);
  const { vista, setVista } = useAppStore();

  const handleChange = (value: Dayjs | null) => {
    message.info(
      `Selected Date: ${value ? value.format("YYYY-MM-DD") : "None"}`
    );
    setDate(value);
  };
  return (
    <div
      className={
        `${vista !== "form" && "hidden"}` + " flex flex-col gap-4 h-full"
      }
    >
      <div className="flex gap-3">
        <Button
          type="default"
          icon={<UnorderedListOutlined />}
          onClick={() => setVista("table")}
          className=""
        >
          Ver todos los lugares
        </Button>
      </div>
      <form action="" className="bg-white rounded-md p-4 h-full">
        <Button type="primary" icon={<SaveFilled />}>
          Guardar
        </Button>
      </form>
    </div>
  );
}
