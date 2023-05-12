import "./components/tables/TableLugares";
import "antd/dist/reset.css";
import TableLugares from "./components/tables/TableLugares";
import TabsLugar from "./components/tabs/TabsLugar";
import { useEffect } from "react";
import { useAppStore } from "./hooks/appStore";

function App() {
  const { setHeight, setWidth } = useAppStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);
  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
    // console.log(window.innerWidth);
  };
  return (
    <main className="flex flex-col p-2 md:p-6 bg-gray-200 w-screen h-screen overflow-auto text-slate-600">
      <TableLugares />
      <TabsLugar />
    </main>
  );
}

export default App;
