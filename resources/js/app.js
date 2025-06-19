import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import 'flowbite';
import './delete-action-alert';
import './theme-toggle';

window.Alpine = Alpine;
Alpine.plugin(mask);
Alpine.start();
