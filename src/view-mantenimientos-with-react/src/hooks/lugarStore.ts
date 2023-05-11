import { create } from "zustand";

interface useAppProps {
  vista: any;
  setLugar: (vista: any) => void;
}
export const useLugarStore = create<useAppProps>()((set) => ({
  vista: "table",
  setLugar: (vista) => set(() => ({ vista: vista })),
}));
