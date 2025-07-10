import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import HeaderComponent from './components/HeaderComponent.vue';

window.Alpine = Alpine;
Alpine.start();

const app = createApp({});
app.component('example-component', ExampleComponent);
app.component('header-component', HeaderComponent);
app.mount('#app');