import { useEffect, useState } from "react";
import "antd/dist/reset.css";
import { DatePicker, message } from "antd";
import { Dayjs } from "dayjs";

function App() {
  const [date, setDate] = useState<Dayjs | null>(null);
  const handleChange = (value: Dayjs | null) => {
    message.info(
      `Selected Date: ${value ? value.format("YYYY-MM-DD") : "None"}`
    );
    setDate(value);
  };
  const api = async () => {
    let headersList = {
      Accept: "*/*",
      "User-Agent": "Thunder Client (https://www.thunderclient.com)",
      Authorization: "Bearer Marn_bdps-2023?_3j--_0sdf20J09J988hj9",
    };

    let response = await fetch("/reservaciones/api/lugares", {
      method: "GET",
      headers: headersList,
    });

    let data = await response.text();
    console.log(JSON.parse(data));
  };

  useEffect(() => {
    api();
  }, []);

  return (
    <div>
      <DatePicker onChange={handleChange} />
      <div style={{ marginTop: 16 }}>
        Selected Date: {date ? date.format("YYYY-MM-DD") : "None"}
      </div>
    </div>
  );
}

export default App;
