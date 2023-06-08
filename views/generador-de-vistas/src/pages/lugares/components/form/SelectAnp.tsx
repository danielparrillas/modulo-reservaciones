// 🖌️ AntDesign
import { Select, Modal } from "antd";
// 🌐 Librerias de terceros
import { useEffect, useState } from "react";
import axios from "axios";
// 😁 Componentes y funciones propias
import { useLugarStore } from "../../../../hooks/lugarStore";

interface ANP {
  id: number;
  nombre: string;
  activo: boolean;
  extension: number;
}

export default function AutocompleteAnp() {
  const { anpId, setAnpId, estaGuardando } = useLugarStore();
  const [options, setOptions] = useState<ANP[]>([]);

  useEffect(() => {
    getANPs();
  }, []);

  const getANPs = async () => {
    await axios
      .get("../areas/api/anp")
      .then((response) => {
        console.log(response); // 👀

        if (typeof response.data === "string") {
          if (response.data.includes("script")) {
            Modal.warning({
              title: "Respuesta del servidor con script",
              content: response.data,
              width: "80%",
            });
          } else {
            Modal.warning({
              title: "Respuesta del servidor no esperada",
              content: (
                <div dangerouslySetInnerHTML={{ __html: response.data }}></div>
              ),
              width: "80%",
            });
          }
        } else if (Array.isArray(response.data)) {
          setOptions(response.data);
        } else {
          Modal.error({
            title: "Respuesta del servidor no esperada",
            content:
              "Formato de la respuesta del servidor con formato incompatible",
          });
        }
      })
      .catch((error) => {
        console.error(error);
        Modal.error({
          title: "Error al llamar las anp",
          content: error.message,
        });
      });
  };

  const onChange = (value: string, option: any) => {
    console.log(`selected ${value}`);
    if (option !== undefined) setAnpId(option.id);
  };
  return (
    <Select
      showSearch
      disabled={estaGuardando}
      value={options.find((anp) => anp.id === anpId)?.nombre}
      onClear={() => setAnpId(undefined)}
      placeholder="Selecciona la anp"
      optionFilterProp="children"
      onChange={onChange}
      filterOption={(input, option) =>
        (option?.label ?? "").toLowerCase().includes(input.toLowerCase())
      }
      options={options.map((anp) => ({
        id: anp.id,
        value: anp.id.toString(),
        label: anp.nombre,
      }))}
      className="w-full"
      allowClear
    />
  );
}
