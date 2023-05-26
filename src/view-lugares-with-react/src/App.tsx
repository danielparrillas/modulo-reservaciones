import { useEffect } from "react";
import "antd/dist/reset.css";
import { useLayoutStore } from "./hooks/layoutStore";
import LugaresPage from "./pages/lugares";

function App() {
  const { setHeight, setWidth } = useLayoutStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);
  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };
  return (
    <div className="flex flex-col p-2 md:p-6 bg-gray-200 w-full h-full overflow-auto text-slate-600">
      <LugaresPage />
    </div>
  );
}

export default App;
