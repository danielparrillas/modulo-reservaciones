import { create } from "zustand";

type Modo = "edicion" | "nuevo";

interface useLugarProps {
  modo: Modo;
  lugar: any;
  setModo: (modo: Modo) => void;
  setLugar: (lugar: any) => void;
}
export const useLugarStore = create<useLugarProps>()((set) => ({
  modo: "nuevo",
  lugar: null,
  setModo: (modo) => set(() => ({ modo: modo })),
  setLugar: (lugar) => set(() => ({ lugar: lugar })),
}));
