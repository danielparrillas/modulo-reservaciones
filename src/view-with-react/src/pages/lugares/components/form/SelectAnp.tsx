// 🖌️ AntDesign
import { Select } from "antd";
// 😁 Componentes y funciones propias
import { useLugarStore } from "../../hooks/lugarStore";

const options = [
  {
    id: 1,
    value: "Montecristo",
    label: "Montecristo",
  },
  {
    id: 2,
    value: "Apaneca Ilamatepec",
    label: "Apaneca Ilamatepec",
  },
  {
    id: 3,
    value: "El Imposible",
    label: "El Imposible",
  },
];

export default function AutocompleteAnp() {
  const { anpId, setAnpId } = useLugarStore();

  const onChange = (value: string, option: any) => {
    console.log(`selected ${value}`);
    if (option !== undefined) setAnpId(option.id);
  };
  const onSearch = (value: string) => {
    console.log("search:", value);
  };
  return (
    <Select
      defaultValue={options.find((option) => option.id === anpId)?.label}
      showSearch
      placeholder="Selecciona la anp"
      optionFilterProp="children"
      onChange={onChange}
      onSearch={onSearch}
      filterOption={(input, option) =>
        (option?.label ?? "").toLowerCase().includes(input.toLowerCase())
      }
      options={options}
      className="w-full"
      allowClear
    />
  );
}
