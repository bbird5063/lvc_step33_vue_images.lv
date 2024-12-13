import './bootstrap';
import { createApp } from 'vue';
//import router from './router'; // в этом проекте не надо

const app = createApp({});

import Index from './components/Index.vue';
app.component('Index', Index);

//app.use(router).mount('#app');
app.mount('#app');