import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link} from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faMoon, faBars, faSun, faChevronRight, faChevronLeft } from '@fortawesome/free-solid-svg-icons'
import AppLayout from './Layouts/AppLayout.vue';
library.add([faMoon, faBars, faSun, faChevronRight, faChevronLeft]);

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
      const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
      page.then((module) => {
                module.default.layout = module.default.layout || AppLayout;
      });
      return page;
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('Head', Head)
            .component('Link', Link)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
