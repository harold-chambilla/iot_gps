import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import Prueba from '../assets/vue/controllers/Prueba.vue';
import { createApp } from 'vue';

const googleMapsApiKey = 'AIzaSyBKG625KcwDUXUIvO0x22JMGYMV7DMqd7Q';
const googleMapsScript = document.createElement('script');
googleMapsScript.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapsApiKey}&libraries=geometry&callback=initMap`;
googleMapsScript.async = true;
googleMapsScript.defer = true;

// Agregar el script al final del cuerpo del documento
document.body.appendChild(googleMapsScript);

window.initMap = () => {}

const app = createApp(Prueba);
app.mount('#app');

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));