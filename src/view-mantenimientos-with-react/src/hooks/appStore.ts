import { create } from "zustand";

type Vista = "table" | "tabs";

interface useAppProps {
  vista: Vista;
  setVista: (vista: Vista) => void;
}
export const useAppStore = create<useAppProps>()((set) => ({
  vista: "table",
  setVista: (vista) => set(() => ({ vista: vista })),
}));
