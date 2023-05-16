import { ReactElement, useEffect } from "react";
import "antd/dist/reset.css";
import { useLayoutStore } from "../../hooks/layoutStore";

interface LayoutProps {
  children: ReactElement | string | number;
}
export default function Layout({ children }: LayoutProps) {
  const { setHeight, setWidth } = useLayoutStore();
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);
  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };
  return (
    <div className="flex flex-col p-2 md:p-6 bg-gray-200 w-screen h-screen overflow-auto text-slate-600">
      {children}
    </div>
  );
}
