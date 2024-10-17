import { usePage } from "@inertiajs/vue3";
import { Notify } from "quasar";

export default function processFlashMessage() {
  const page = usePage();
  let flash = page.props.flash;

  if (flash.info) {
    Notify.create({
      message: flash.info,
      icon: 'info',
      color: 'blue',
    });
  }

  if (flash.success) {
    Notify.create({
      message: flash.success,
      icon: 'check',
      color: 'green',
    });
  }

  if (flash.warning) {
    Notify.create({
      message: flash.warning,
      icon: 'warning',
      color: 'yellow',
    });
  }

  if (flash.error) {
    Notify.create({
      message: flash.error,
      icon: 'error',
      color: 'red',
    });
  }
}
