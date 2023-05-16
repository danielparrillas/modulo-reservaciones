import { useLocation } from "react-router-dom";

export default function LugarPage() {
  return <h1>Lugar {useLocation().pathname}</h1>;
}
