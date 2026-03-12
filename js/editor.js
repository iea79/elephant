wp.domReady(() => {
    // Отключить стили кнопок по умолчанию
    wp.blocks.unregisterBlockStyle('core/button', 'outline');
    wp.blocks.unregisterBlockStyle('core/button', 'fill');
    // Регистрируем новые стили кнопок и устанавливаем один из них по умолчанию
    wp.blocks.registerBlockStyle('core/button', { name: 'fill', label: 'По умолчанию', isDefault: true });
    wp.blocks.registerBlockStyle('core/button', { name: 'outline', label: 'С обводкой' });
    wp.blocks.registerBlockStyle('core/button', { name: 'secondary-button', label: 'Дополнительный' });
    wp.blocks.registerBlockStyle('core/button', { name: 'success-button', label: 'Успешный' });
    wp.blocks.registerBlockStyle('core/button', { name: 'danger-button', label: 'Внимание' });
});
