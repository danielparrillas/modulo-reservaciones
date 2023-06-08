import { create } from "zustand";

interface useServicioProps {
  estaGuardando: boolean;
  servicioSeleccionadoId?: number;
  disponibilidadGrupoId?: number;
  setGuardando: (guardando: boolean) => void;
  setServicioSeleccionadoId: (id?: number) => void;
  setDisponibilidadGrupoId: (id?: number) => void;
}
export const useServicioStore = create<useServicioProps>()((set) => ({
  estaGuardando: false,
  isOpenForm: false,
  setGuardando: (estaGuardando) =>
    set(() => ({ estaGuardando: estaGuardando })),
  setServicioSeleccionadoId: (id) =>
    set(() => ({ servicioSeleccionadoId: id })),
  setDisponibilidadGrupoId: (id) => set(() => ({ disponibilidadGrupoId: id })),
}));
