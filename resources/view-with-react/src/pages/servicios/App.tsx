import { useState, useEffect } from "react";
import "antd/dist/reset.css";
import Modal from "antd/es/modal/Modal";
import TableServicios from "./tables/TableServicios";
import { useAppStore } from "../lugares/hooks/appStore";

function App() {
  const { setHeight, setWidth } = useAppStore();
  const [isModalOpen, setIsModalOpen] = useState(false);
  useEffect(() => {
    window.addEventListener("resize", handleResize);
  }, []);

  const handleResize = () => {
    setHeight(window.innerHeight);
    setWidth(window.innerWidth);
  };

  const showModal = () => {
    setIsModalOpen(true);
  };

  const handleOk = () => {
    setIsModalOpen(false);
  };

  const handleCancel = () => {
    setIsModalOpen(false);
  };
  return (
    <div
      className="flex flex-col p-2 md:p-6 w-full overflow-auto text-neutral-700 h-full"
      style={{
        height: window.innerHeight - 50,
      }}
    >
      <TableServicios onClickRow={showModal} />
      <Modal
        title="Basic Modal"
        open={isModalOpen}
        onOk={handleOk}
        onCancel={handleCancel}
      >
        <p>Some contents...</p>
        <p>Some contents...</p>
        <p>Some contents...</p>
      </Modal>
    </div>
  );
}

export default App;
