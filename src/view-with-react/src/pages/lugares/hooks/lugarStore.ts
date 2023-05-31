import { create } from "zustand";

type Modo = "edicion" | "nuevo" | "guardando";

interface useLugarProps {
  modo: Modo;
  lugarSeleccionado?: number;
  anpId?: number;
  tab: number | string;
  setModo: (modo: Modo) => void;
  setLugar: (lugar: any) => void;
  setTab: (tab: number | string) => void;
  setAnpId: (id: number) => void;
}
export const useLugarStore = create<useLugarProps>()((set) => ({
  modo: "nuevo",
  lugarSeleccionado: undefined,
  tab: "1",
  setAnpId: (id) => set(() => ({ anpId: id })),
  setModo: (modo) => set(() => ({ modo: modo })),
  setLugar: (lugar) => set(() => ({ lugarSeleccionado: lugar })),
  setTab: (tab) => set(() => ({ tab: tab })),
}));
