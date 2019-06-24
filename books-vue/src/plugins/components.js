import BaseButton from "../components/BaseButton";
import BaseInput from "../components/BaseInput";
import BaseTable from "../components/BaseTable";
import Card from "../components/Card";
import Modal from "../components/Modal";


export default {
  install(Vue) {
    Vue.component(BaseButton.name, BaseButton);
    Vue.component(BaseInput.name, BaseInput);
    Vue.component(BaseTable.name, BaseTable);
    Vue.component(Card.name, Card);
    Vue.component(Modal.name, Modal);
  }
};
