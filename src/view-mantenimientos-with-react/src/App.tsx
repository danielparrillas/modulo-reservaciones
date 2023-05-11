import "./components/tables/TableLugares";
import "antd/dist/reset.css";
import TableLugares from "./components/tables/TableLugares";
import FormLugar from "./components/forms/FormLugar";

function App() {
  return (
    <main className="flex flex-col p-2 md:p-6 bg-gray-200 w-screen h-screen overflow-auto ">
      <TableLugares />
      <FormLugar />
    </main>
  );
}

export default App;
