import { useEffect, useState } from "react";
import { Dayjs } from "dayjs";
import { useAppStore } from "../../hooks/appStore";
import { UnorderedListOutlined, SaveFilled } from "@ant-design/icons";
import { DatePicker, message, Space, Button, Tabs } from "antd";
import { TabsProps } from "antd";

const tabs: TabsProps["items"] = [
  {
    key: "1",
    label: `Información`,
    children: <a>sad</a>,
  },
  {
    key: "2",
    label: `Servicios`,
    children: `Content of Tab Pane 2`,
  },
  {
    key: "3",
    label: `Periodos inactivos`,
    children: `Content of Tab Pane 3`,
  },
];

export default function TabsLugar() {
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
        `${vista !== "tabs" && "hidden"}` + " flex flex-col gap-4 h-full"
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
      <Tabs
        defaultActiveKey="1"
        items={tabs}
        className="bg-white p-4 rounded-md h-full"
      />
    </div>
  );
}
