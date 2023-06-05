import { create } from "zustand";

interface useLugarProps {
  estaGuardando: boolean;
  lugarSeleccionado?: number;
  anpId?: number;
  tab: number | string;
  setGuardando: (guardando: boolean) => void;
  setLugarSeleccionado: (lugar: any) => void;
  setTab: (tab: number | string) => void;
  setAnpId: (id: number | undefined) => void;
}
export const useLugarStore = create<useLugarProps>()((set) => ({
  estaGuardando: false,
  lugarSeleccionado: undefined,
  tab: "1",
  setAnpId: (id) => set(() => ({ anpId: id })),
  setGuardando: (estaGuardando) =>
    set(() => ({ estaGuardando: estaGuardando })),
  setLugarSeleccionado: (lugar) => set(() => ({ lugarSeleccionado: lugar })),
  setTab: (tab) => set(() => ({ tab: tab })),
}));
