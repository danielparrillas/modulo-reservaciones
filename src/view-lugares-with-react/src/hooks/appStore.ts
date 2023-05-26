import { create } from "zustand";

type Vista = "lista" | "detalle";
interface useAppProps {
  width: number;
  height: number;
  setWidth: (width: number) => void;
  setHeight: (height: number) => void;
  vista: Vista;
  setVista: (vista: Vista) => void;
}
export const useAppStore = create<useAppProps>()((set) => ({
  width: window.innerWidth,
  height: window.innerHeight,
  vista: "lista",
  setWidth: (width) => set(() => ({ width: width })),
  setHeight: (height) => set(() => ({ height: height })),
  setVista: (vista) => set(() => ({ vista: vista })),
}));
