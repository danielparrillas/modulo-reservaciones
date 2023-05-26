import { create } from "zustand";

interface useLayoutProps {
  width: number;
  height: number;
  setWidth: (width: number) => void;
  setHeight: (height: number) => void;
}
export const useLayoutStore = create<useLayoutProps>()((set) => ({
  width: window.innerWidth,
  height: window.innerHeight,
  setWidth: (width) => set(() => ({ width: width })),
  setHeight: (height) => set(() => ({ height: height })),
}));
