import { create } from "zustand";

type Vista = "table" | "tabs";

interface useAppProps {
  vista: Vista;
  width: number;
  height: number;
  setVista: (vista: Vista) => void;
  setWidth: (width: number) => void;
  setHeight: (height: number) => void;
}
export const useAppStore = create<useAppProps>()((set) => ({
  vista: "table",
  width: window.innerWidth,
  height: window.innerHeight,
  setVista: (vista) => set(() => ({ vista: vista })),
  setWidth: (width) => set(() => ({ width: width })),
  setHeight: (height) => set(() => ({ height: height })),
}));
