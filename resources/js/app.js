import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import 'flowbite';

window.Alpine = Alpine;
Alpine.plugin(mask);
Alpine.start();
