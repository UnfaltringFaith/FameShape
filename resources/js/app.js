import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import HeaderComponent from './components/HeaderComponent.vue';

const app = createApp(App);
app.use(router);
app.component('header-component', HeaderComponent);
app.mount('#app');