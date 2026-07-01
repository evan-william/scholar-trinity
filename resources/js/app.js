import './bootstrap';

import { createApp } from 'vue';
import RegistrationShell from './vue/RegistrationShell.vue';

const vueComponents = {
    RegistrationShell,
};

document.querySelectorAll('[data-vue-component]').forEach((mount) => {
    const component = vueComponents[mount.dataset.vueComponent];

    if (!component) {
        return;
    }

    const props = mount.dataset.vueProps ? JSON.parse(mount.dataset.vueProps) : {};
    createApp(component, props).mount(mount);
});
