import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import MainLayout from './Layouts/MainLayout.vue';

createInertiaApp({
    title: (title) => `${title} - FoodMart`,
    // 👇 ЗМІНЕНА ЧАСТИНА ДЛЯ ДІАГНОСТИКИ 👇
    resolve: (name) => {
        console.log('🔍 Inertia шукає сторінку:', name);
        
        // Отримуємо всі файли
        const pages = import.meta.glob('./Pages/**/*.vue');
        
        // Формуємо шлях, який очікуємо
        const expectedPath = `./Pages/${name}.vue`;
        console.log('📂 Очікуваний шлях файлу:', expectedPath);
        
        // Перевіряємо, чи є такий файл у списку
        if (!pages[expectedPath]) {
            console.error('❌ ФАЙЛ НЕ ЗНАЙДЕНО! Ось список усіх доступних сторінок:', Object.keys(pages));
        } else {
            console.log('✅ Файл знайдено!');
        }

        const page = resolvePageComponent(expectedPath, pages);

        page.then((module) => {
            module.default.layout = module.default.layout || MainLayout;
        });

        return page;
    },
    // 👆 КІНЕЦЬ ЗМІН 👆
    
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});