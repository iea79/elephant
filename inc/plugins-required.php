<?php

/**
 * Функции для управления обязательными плагинами темы
 */

// Защита от прямого доступа
defined('ABSPATH') || exit;

/**
 * Получает список необходимых плагинов
 */
function theme_get_required_plugins() {
    return apply_filters('theme_required_plugins', array(
        'smart-custom-fields' => array(
            'name' => 'Smart Custom Fields',
            'slug' => 'smart-custom-fields', 
            'file' => 'smart-custom-fields/smart-custom-fields.php',
            'required' => true,
            'description' => 'Необходим для управления кастомными полями.',
            'source' => 'repo',
        ),

        'cyr2lat' => array(
            'name' => 'Cyr to Lat',
            'slug' => 'cyr2lat',
            'file' => 'cyr2lat/cyr-to-lat.php',
            'required' => false, 
            'description' => 'Транслитерирует кириллические URL в латинские.',
            'source' => 'repo',
        ),

        'svg-support' => array(
            'name' => 'SVG Support',
            'slug' => 'svg-support',
            'file' => 'svg-support/svg-support.php',
            'required' => false,
            'description' => 'Комплексное решение SVG для WordPress.',
            'source' => 'repo',
        ),

        'webp-converter-for-media' => array(
            'name' => 'WEBP Converter for Media',
            'slug' => 'webp-converter-for-media',
            'file' => 'webp-converter-for-media/webp-converter-for-media.php',
            'required' => false,
            'description' => 'Комплексное решение c Image для WordPress.',
            'source' => 'repo',
        ),

        'wpforms-lite' => array(
            'name' => 'WPForms',
            'slug' => 'wpforms-lite',
            'file' => 'wpforms-lite/wpforms.php',
            'required' => false,
            'description' => 'WordPress плагин для создания контактных форм.',
            'source' => 'repo',
        ),
    ));
}

/**
 * Проверяет, установлены ли все необходимые плагины
 */
function theme_check_required_plugins() {
    $plugins = theme_get_required_plugins();
    foreach ($plugins as $plugin) {
        if ($plugin['required'] && !is_plugin_active($plugin['file'])) {
            return false;
        }
    }
    return true;
}

/**
 * Проверяет, установлен ли плагин
 */
function is_plugin_installed($plugin_file) {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    $all_plugins = get_plugins();
    return isset($all_plugins[$plugin_file]);
}

/**
 * Получает статусы всех необходимых плагинов
 */
function theme_get_plugins_status() {
    $plugins = theme_get_required_plugins();
    $status = array();
    
    foreach ($plugins as $key => $plugin) {
        $is_installed = is_plugin_installed($plugin['file']);
        $is_active = is_plugin_active($plugin['file']);
        
        // Получаем информацию об установленном плагине
        $plugin_data = array();
        if ($is_installed && function_exists('get_plugin_data')) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin['file']);
        }
        
        $status[$key] = array(
            'name' => $plugin['name'],
            'installed' => $is_installed,
            'active' => $is_active,
            'required' => $plugin['required'],
            'file' => $plugin['file'],
            'slug' => $plugin['slug'],
            'description' => $plugin['description'],
            'source' => $plugin['source'] ?? 'repo',
            'plugin_uri' => $plugin_data['PluginURI'] ?? '',
            'status_class' => $is_active ? 'active' : ($is_installed ? 'inactive' : 'not-installed')
        );
    }
    
    return $status;
}

/**
 * AJAX обработчик для установки плагинов
 */
function theme_ajax_install_plugin() {
    // Проверка прав и nonce
    if (!current_user_can('install_plugins')) {
        wp_send_json_error('Недостаточно прав');
    }

    if (!check_ajax_referer('theme_plugins_nonce', 'nonce', false)) {
        wp_send_json_error('Неверный nonce');
    }

    $plugin_slug = sanitize_text_field($_POST['plugin_slug'] ?? '');
    if (empty($plugin_slug)) {
        wp_send_json_error('Не указан slug плагина');
    }
    $plugins = theme_get_required_plugins();
    
    if (!isset($plugins[$plugin_slug])) {
        wp_send_json_error('Плагин не найден в списке');
    }
    
    $plugin = $plugins[$plugin_slug];
    
    // Установка
    if (!is_plugin_installed($plugin['file'])) {
        $result = theme_install_plugin($plugin);
        if (!$result['success']) {
            wp_send_json_error('Ошибка установки: ' . $result['message']);
        }
    }
    
    // Активация
    if (!is_plugin_active($plugin['file'])) {
        $activation_result = theme_activate_plugin($plugin['file']);
        if (!$activation_result['success']) {
            wp_send_json_error('Ошибка активации: ' . $activation_result['message']);
        }
    }
    
    wp_send_json_success('Плагин успешно установлен и активирован');
}
add_action('wp_ajax_theme_install_plugin', 'theme_ajax_install_plugin');

/**
 * Улучшенная функция установки плагина
 */
function theme_install_plugin($plugin) {
    if (!current_user_can('install_plugins')) {
        return array('success' => false, 'message' => 'Недостаточно прав');
    }
    
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    
    // Создаем экран для upgrader
    $upgrader_skin = new Automatic_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader($upgrader_skin);
    
    try {
        // Для плагинов из репозитория
        if ($plugin['source'] === 'repo') {
            $api = plugins_api('plugin_information', array(
                'slug' => $plugin['slug'],
                'fields' => array(
                    'short_description' => false,
                    'sections' => false,
                    'requires' => false,
                    'rating' => false,
                    'ratings' => false,
                    'downloaded' => false,
                    'last_updated' => false,
                    'added' => false,
                    'tags' => false,
                    'compatibility' => false,
                    'homepage' => false,
                    'donate_link' => false,
                ),
            ));
            
            if (is_wp_error($api)) {
                return array('success' => false, 'message' => $api->get_error_message());
            }
            
            $result = $upgrader->install($api->download_link);
        }
        
        if (is_wp_error($result)) {
            return array('success' => false, 'message' => $result->get_error_message());
        }
        
        if (!$result) {
            return array('success' => false, 'message' => 'Неизвестная ошибка при установке');
        }
        
        return array('success' => true, 'message' => 'Плагин успешно установлен');
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => $e->getMessage());
    }
}

/**
 * Активация плагина
 */
function theme_activate_plugin($plugin_file) {
    if (!current_user_can('activate_plugins')) {
        return array('success' => false, 'message' => 'Недостаточно прав для активации');
    }
    
    $result = activate_plugin($plugin_file);
    
    if (is_wp_error($result)) {
        return array('success' => false, 'message' => $result->get_error_message());
    }
    
    return array('success' => true, 'message' => 'Плагин успешно активирован');
}

/**
 * Массовая установка плагинов
 */
function theme_bulk_install_plugins($type = 'required') {
    if (!current_user_can('install_plugins')) {
        return array('success' => false, 'message' => 'Недостаточно прав');
    }

    $plugins_status = theme_get_plugins_status();
    $results = array();
    $installed_plugins = array();
    
    foreach ($plugins_status as $plugin_slug => $plugin) {
        // Фильтр по типу
        if ($type === 'required' && !$plugin['required']) {
            continue;
        }
        
        if (!$plugin['active']) {
            if (!$plugin['installed']) {
                // Установка
                $result = theme_install_plugin($plugin);
                $results[$plugin_slug] = $result;
                
                // Активация после установки
                if ($result['success']) {
                    $activation_result = theme_activate_plugin($plugin['file']);
                    $results[$plugin_slug . '_activation'] = $activation_result;
                    
                    if ($activation_result['success']) {
                        $installed_plugins[] = array(
                            'slug' => $plugin_slug,
                            'name' => $plugin['name'],
                            'required' => $plugin['required']
                        );
                    }
                }
            } else {
                // Только активация
                $result = theme_activate_plugin($plugin['file']);
                $results[$plugin_slug] = $result;
                
                if ($result['success']) {
                    $installed_plugins[] = array(
                        'slug' => $plugin_slug,
                        'name' => $plugin['name'],
                        'required' => $plugin['required']
                    );
                }
            }
        }
    }
    
    return array(
        'results' => $results,
        'installed_plugins' => $installed_plugins
    );
}

/**
 * AJAX для массовой установки
 */
function theme_ajax_bulk_install() {
    if (!current_user_can('install_plugins')) {
        wp_send_json_error('Недостаточно прав');
    }

    if (!check_ajax_referer('theme_plugins_nonce', 'nonce', false)) {
        wp_send_json_error('Неверный nonce');
    }
    
    $type = sanitize_text_field($_POST['type'] ?? 'required');
    $bulk_result = theme_bulk_install_plugins($type);
    
    // Проверяем, есть ли ошибки
    $has_errors = false;
    foreach ($bulk_result['results'] as $result) {
        if (isset($result['success']) && !$result['success']) {
            $has_errors = true;
            break;
        }
    }
    
    if ($has_errors) {
        wp_send_json_error(array(
            'results' => $bulk_result['results'],
            'message' => 'В процессе установки возникли ошибки'
        ));
    } else {
        wp_send_json_success(array(
            'results' => $bulk_result['results'],
            'installed_plugins' => $bulk_result['installed_plugins'],
            'message' => 'Массовая установка завершена успешно'
        ));
    }
}
add_action('wp_ajax_theme_bulk_install', 'theme_ajax_bulk_install');

/**
 * Страница установки плагинов
 */
function theme_plugins_page() {
    add_plugins_page(
        'Необходимые плагины',
        'Плагины темы <span class="update-plugins count-0"><span class="plugin-count"></span></span>',
        'install_plugins',
        'theme-required-plugins',
        'theme_plugins_page_content'
    );
}
add_action('admin_menu', 'theme_plugins_page');

/**
 * Регистрация скриптов и стилей
 */
function theme_plugins_admin_scripts($hook) {
    if ($hook !== 'plugins_page_theme-required-plugins') {
        return;
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_style('plugin-install');
    wp_enqueue_script('plugin-install');
    wp_enqueue_script('updates');
    
    // Стили для страницы
    wp_add_inline_style('plugin-install', '
        .theme-required-plugins .bulk-actions-card {
            background: #fff;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin: 20px 0;
            padding: 20px;
        }
        .theme-required-plugins .bulk-actions-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .theme-required-plugins .required-badge,
        .theme-required-plugins .optional-badge {
            display: inline-block;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 2px;
            margin-left: 8px;
            text-transform: uppercase;
            font-weight: 600;
            vertical-align: middle;
        }
        .theme-required-plugins .required-badge {
            background: #f0b849;
            color: #1d2327;
        }
        .theme-required-plugins .optional-badge {
            background: #f0f0f1;
            color: #50575e;
        }
        .theme-required-plugins .plugin-status-badge {
            display: inline-block;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 2px;
            margin-left: 8px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .theme-required-plugins .status-active {
            background: #d4edda;
            color: #155724;
        }
        .theme-required-plugins .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .theme-required-plugins .status-not-installed {
            background: #fff3cd;
            color: #856404;
        }
        .theme-required-plugins .row-actions .installing {
            color: #2271b1;
            font-weight: 600;
        }
        .theme-required-plugins .row-actions .error {
            color: #d63638;
            font-weight: 600;
        }
        .theme-required-plugins .wp-list-table.plugins {
            margin-top: 20px;
        }
        .theme-required-plugins .wp-list-table th.check-column,
        .theme-required-plugins .wp-list-table td.check-column {
            display: none;
        }
        @media screen and (max-width: 782px) {
            .theme-required-plugins .bulk-actions-buttons {
                flex-direction: column;
            }
            .theme-required-plugins .bulk-actions-buttons .button {
                width: 100%;
                text-align: center;
            }
        }
    ');
    
    // Добавляем inline скрипт с локализацией
    add_action('admin_footer', 'theme_plugins_admin_footer_script');
}
add_action('admin_enqueue_scripts', 'theme_plugins_admin_scripts');

/**
 * Inline скрипт с локализацией
 */
function theme_plugins_admin_footer_script() {
    ?>
    <script type="text/javascript">
    var themePlugins = {
        ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('theme_plugins_nonce'); ?>',
        strings: {
            install: '<?php echo esc_js(__('Установить', 'textdomain')); ?>',
            activate: '<?php echo esc_js(__('Активировать', 'textdomain')); ?>',
            installed: '<?php echo esc_js(__('Активен', 'textdomain')); ?>',
            installing: '<?php echo esc_js(__('Установка...', 'textdomain')); ?>',
            activating: '<?php echo esc_js(__('Активация...', 'textdomain')); ?>',
            required: '<?php echo esc_js(__('Обязательный', 'textdomain')); ?>',
            optional: '<?php echo esc_js(__('Дополнительный', 'textdomain')); ?>',
            bulk_install_required: '<?php echo esc_js(__('Установить обязательные плагины', 'textdomain')); ?>',
            bulk_install_all: '<?php echo esc_js(__('Установить все плагины', 'textdomain')); ?>'
        }
    };
    </script>
    <?php
}

/**
 * Контент страницы плагинов
 */
function theme_plugins_page_content() {
    $plugins_status = theme_get_plugins_status();
    $missing_required = array_filter($plugins_status, function($p) { 
        return $p['required'] && !$p['active']; 
    });
    $missing_optional = array_filter($plugins_status, function($p) { 
        return !$p['required'] && !$p['active']; 
    });
    
    $missing_count = count($missing_required) + count($missing_optional);
    ?>
    <div class="wrap theme-required-plugins">
        <h1 class="wp-heading-inline"><?php echo esc_html__('Плагины темы', 'textdomain'); ?></h1>
        <hr class="wp-header-end">
        
        <?php if ($missing_count > 0): ?>
        <div class="bulk-actions-card">
            <h2><?php echo esc_html__('Быстрая установка', 'textdomain'); ?></h2>
            <p class="description"><?php echo esc_html__('Установите все необходимые плагины одним кликом.', 'textdomain'); ?></p>
            
            <div class="bulk-actions-buttons">
                <?php if (!empty($missing_required)): ?>
                <button type="button" class="button button-primary button-hero bulk-install-btn" data-type="required">
                    <?php 
                    printf(
                        esc_html__('Установить обязательные плагины (%d)', 'textdomain'), 
                        count($missing_required)
                    ); 
                    ?>
                </button>
                <?php endif; ?>
                
                <?php if (!empty($missing_optional)): ?>
                <button type="button" class="button button-hero bulk-install-btn" data-type="all">
                    <?php 
                    printf(
                        esc_html__('Установить все плагины (%d)', 'textdomain'), 
                        $missing_count
                    ); 
                    ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="tablenav top">
            <div class="tablenav-pages one-page">
                <span class="displaying-num">
                    <?php 
                    printf(
                        esc_html(_n('%s элемент', '%s элемента', count($plugins_status), 'textdomain')),
                        number_format_i18n(count($plugins_status))
                    ); 
                    ?>
                </span>
            </div>
            <br class="clear">
        </div>
        
        <h2 class="screen-reader-text"><?php echo esc_html__('Список плагинов', 'textdomain'); ?></h2>
        
        <table class="wp-list-table widefat plugins">
            <thead>
                <tr>
                    <th scope="col" id="name" class="manage-column column-name column-primary"><?php echo esc_html__('Плагин', 'textdomain'); ?></th>
                    <th scope="col" id="description" class="manage-column column-description"><?php echo esc_html__('Описание', 'textdomain'); ?></th>
                    <th scope="col" id="type" class="manage-column column-type"><?php echo esc_html__('Тип', 'textdomain'); ?></th>
                    <th scope="col" id="status" class="manage-column column-status"><?php echo esc_html__('Статус', 'textdomain'); ?></th>
                </tr>
            </thead>
            
            <tbody id="the-list">
                <?php foreach ($plugins_status as $slug => $plugin): ?>
                <?php 
                $row_class = $plugin['active'] ? 'active' : ($plugin['installed'] ? 'inactive' : 'not-installed');
                $row_class .= $plugin['required'] ? ' required' : ' optional';
                ?>
                <tr class="<?php echo esc_attr($row_class); ?>" data-slug="<?php echo esc_attr($slug); ?>">
                    <td class="plugin-title column-primary">
                        <strong><?php echo esc_html($plugin['name']); ?></strong>
                        <div class="row-actions visible">
                            <?php if ($plugin['active']): ?>
                                <span class="deactivate">
                                    <span class="installed-text"><?php echo esc_html__('Активен', 'textdomain'); ?></span>
                                </span>
                            <?php elseif ($plugin['installed']): ?>
                                <span class="activate">
                                    <a href="#" class="install-single-btn" data-plugin="<?php echo esc_attr($slug); ?>">
                                        <?php echo esc_html__('Активировать', 'textdomain'); ?>
                                    </a>
                                </span>
                            <?php else: ?>
                                <span class="install">
                                    <a href="#" class="install-single-btn" data-plugin="<?php echo esc_attr($slug); ?>">
                                        <?php echo esc_html__('Установить', 'textdomain'); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="toggle-row">
                            <span class="screen-reader-text"><?php echo esc_html__('Показать больше деталей', 'textdomain'); ?></span>
                        </button>
                    </td>
                    
                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p><?php echo esc_html($plugin['description']); ?></p>
                        </div>
                    </td>
                    
                    <td class="column-type">
                        <?php if ($plugin['required']): ?>
                            <span class="required-badge"><?php echo esc_html__('Обязательный', 'textdomain'); ?></span>
                        <?php else: ?>
                            <span class="optional-badge"><?php echo esc_html__('Дополнительный', 'textdomain'); ?></span>
                        <?php endif; ?>
                    </td>
                    
                    <td class="column-status">
                        <?php if ($plugin['active']): ?>
                            <span class="plugin-status-badge status-active"><?php echo esc_html__('Активен', 'textdomain'); ?></span>
                        <?php elseif ($plugin['installed']): ?>
                            <span class="plugin-status-badge status-inactive"><?php echo esc_html__('Не активен', 'textdomain'); ?></span>
                        <?php else: ?>
                            <span class="plugin-status-badge status-not-installed"><?php echo esc_html__('Не установлен', 'textdomain'); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-name column-primary"><?php echo esc_html__('Плагин', 'textdomain'); ?></th>
                    <th scope="col" class="manage-column column-description"><?php echo esc_html__('Описание', 'textdomain'); ?></th>
                    <th scope="col" class="manage-column column-type"><?php echo esc_html__('Тип', 'textdomain'); ?></th>
                    <th scope="col" class="manage-column column-status"><?php echo esc_html__('Статус', 'textdomain'); ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="tablenav bottom">
            <div class="tablenav-pages one-page">
                <span class="displaying-num">
                    <?php 
                    printf(
                        esc_html(_n('%s элемент', '%s элемента', count($plugins_status), 'textdomain')),
                        number_format_i18n(count($plugins_status))
                    ); 
                    ?>
                </span>
            </div>
            <br class="clear">
        </div>
        
        <?php if ($missing_count === 0): ?>
        <div class="updated notice notice-success is-dismissible">
            <p><?php echo esc_html__('🎉 Все плагины установлены и активированы!', 'textdomain'); ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Обновление счетчиков
        function updateCounters() {
            var requiredCount = 0;
            var optionalCount = 0;
            var activeCount = 0;
            
            $('tr[data-slug]').each(function() {
                var $row = $(this);
                var isRequired = $row.hasClass('required');
                var isActive = $row.hasClass('active');
                
                if (isActive) {
                    activeCount++;
                }
                
                if (!isActive) {
                    if (isRequired) {
                        requiredCount++;
                    } else {
                        optionalCount++;
                    }
                }
            });
            
            var totalMissing = requiredCount + optionalCount;
            
            // Обновляем кнопки массовой установки
            var $requiredBtn = $('.bulk-install-btn[data-type="required"]');
            var $allBtn = $('.bulk-install-btn[data-type="all"]');
            
            if ($requiredBtn.length) {
                $requiredBtn.text(themePlugins.strings.bulk_install_required + ' (' + requiredCount + ')');
                if (requiredCount === 0) {
                    $requiredBtn.hide();
                } else {
                    $requiredBtn.show();
                }
            }
            
            if ($allBtn.length) {
                $allBtn.text(themePlugins.strings.bulk_install_all + ' (' + totalMissing + ')');
                if (totalMissing === 0) {
                    $allBtn.hide();
                } else {
                    $allBtn.show();
                }
            }
            
            // Показываем/скрываем карточку массовой установки
            if (totalMissing === 0) {
                $('.bulk-actions-card').hide();
            } else {
                $('.bulk-actions-card').show();
            }
            
            return {
                required: requiredCount,
                optional: optionalCount,
                totalMissing: totalMissing,
                active: activeCount
            };
        }
        
        // Обновление строки плагина
        function updatePluginRow(pluginSlug, status) {
            var $row = $('tr[data-slug="' + pluginSlug + '"]');
            var $statusCell = $row.find('.column-status');
            var $actions = $row.find('.row-actions');
            
            // Обновляем классы строки
            $row.removeClass('active inactive not-installed').addClass(status);
            
            // Обновляем статус
            if (status === 'active') {
                $statusCell.html('<span class="plugin-status-badge status-active">' + themePlugins.strings.installed + '</span>');
                $actions.html('<span class="deactivate"><span class="installed-text">' + themePlugins.strings.installed + '</span></span>');
            } else if (status === 'inactive') {
                $statusCell.html('<span class="plugin-status-badge status-inactive">' + themePlugins.strings.inactive + '</span>');
                $actions.html('<span class="activate"><a href="#" class="install-single-btn" data-plugin="' + pluginSlug + '">' + themePlugins.strings.activate + '</a></span>');
            }
            
            // Обновляем счетчики
            updateCounters();
        }
        
        // Одиночная установка
        $(document).on('click', '.install-single-btn', function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var pluginSlug = $link.data('plugin');
            var $row = $link.closest('tr');
            var $actions = $row.find('.row-actions');
            var $statusCell = $row.find('.column-status');
            
            // Сохраняем исходный текст
            var originalText = $link.text();
            var isInstall = originalText === themePlugins.strings.install;
            
            // Меняем текст
            $link.text(isInstall ? themePlugins.strings.installing : themePlugins.strings.activating);
            $actions.addClass('installing');
            $statusCell.html('<span class="plugin-status-badge" style="background: #cfe2ff; color: #084298;">' + 
                            (isInstall ? themePlugins.strings.installing : themePlugins.strings.activating) + '</span>');
            
            // Отправляем запрос
            $.post(themePlugins.ajaxurl, {
                action: 'theme_install_plugin',
                plugin_slug: pluginSlug,
                nonce: themePlugins.nonce
            }, function(response) {
                $actions.removeClass('installing');
                
                if (response.success) {
                    updatePluginRow(pluginSlug, 'active');
                    
                    // Показываем уведомление
                    showNotice(response.data, 'success');
                } else {
                    $link.text(originalText);
                    $statusCell.html('<span class="plugin-status-badge status-not-installed">' + themePlugins.strings.error + '</span>');
                    
                    // Показываем ошибку в строке действий
                    $actions.html('<span class="error">' + themePlugins.strings.error + ': ' + response.data + '</span>');
                    
                    setTimeout(function() {
                        $actions.html('<span class="' + (isInstall ? 'install' : 'activate') + '">' +
                            '<a href="#" class="install-single-btn" data-plugin="' + pluginSlug + '">' + originalText + '</a>' +
                            '</span>');
                    }, 3000);
                    
                    showNotice(response.data, 'error');
                }
            }).fail(function(xhr, status, error) {
                $actions.removeClass('installing');
                $link.text(originalText);
                $statusCell.html('<span class="plugin-status-badge status-not-installed">' + themePlugins.strings.error + '</span>');
                showNotice('Ошибка сети: ' + error, 'error');
            });
        });
        
        // Массовая установка
        $('.bulk-install-btn').on('click', function() {
            var $btn = $(this);
            var type = $btn.data('type');
            var $card = $btn.closest('.bulk-actions-card');
            var $status = $card.find('.bulk-status');
            var $statusText = $status.find('.status-text');
            
            var originalText = $btn.text();
            $btn.prop('disabled', true).text(themePlugins.strings.installing + '...');
            $status.show();
            $statusText.html('<span class="spinner is-active" style="float: none; vertical-align: middle;"></span> ' + 
                           themePlugins.strings.installing + '...');
            
            $.post(themePlugins.ajaxurl, {
                action: 'theme_bulk_install',
                type: type,
                nonce: themePlugins.nonce
            }, function(response) {
                if (response.success) {
                    $statusText.html('✅ ' + response.data.message);
                    $btn.text('✅ ' + themePlugins.strings.installed);
                    
                    // Обновляем установленные плагины
                    if (response.data.installed_plugins) {
                        response.data.installed_plugins.forEach(function(plugin) {
                            updatePluginRow(plugin.slug, 'active');
                        });
                    }
                    
                    setTimeout(function() {
                        $status.hide();
                        var counters = updateCounters();
                        $btn.text(originalText.replace(/\(\d+\)/, '(' + (type === 'required' ? counters.required : counters.totalMissing) + ')'))
                            .prop('disabled', false);
                    }, 2000);
                    
                } else {
                    $statusText.html('❌ ' + response.data.message);
                    $btn.text('❌ ' + themePlugins.strings.error).prop('disabled', false);
                    
                    setTimeout(function() {
                        $status.hide();
                        $btn.text(originalText);
                    }, 5000);
                }
            }).fail(function(xhr, status, error) {
                $statusText.html('❌ Ошибка сети');
                $btn.text('❌ ' + themePlugins.strings.error).prop('disabled', false);
                
                setTimeout(function() {
                    $status.hide();
                    $btn.text(originalText);
                }, 5000);
            });
        });
        
        // Вспомогательная функция для уведомлений
        function showNotice(message, type) {
            var noticeClass = type === 'error' ? 'notice-error' : 
                             type === 'success' ? 'notice-success' : 'notice-info';
            
            var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible" style="position: fixed; top: 40px; right: 20px; z-index: 10000;">' +
                '<p>' + message + '</p>' +
                '<button type="button" class="notice-dismiss"></button>' +
                '</div>').appendTo('body');
            
            setTimeout(function() {
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
            
            $notice.on('click', '.notice-dismiss', function() {
                $notice.remove();
            });
        }
        
        // Инициализация
        updateCounters();
    });
    </script>
    <?php
}

/**
 * Обновляет счетчик в меню админ-панели
 */
function theme_update_plugins_menu_counter() {
    global $menu;
    
    $plugins_status = theme_get_plugins_status();
    $missing_required = array_filter($plugins_status, function($p) { 
        return $p['required'] && !$p['active']; 
    });
    $missing_optional = array_filter($plugins_status, function($p) { 
        return !$p['required'] && !$p['active']; 
    });
    
    $missing_count = count($missing_required) + count($missing_optional);
    
    // Обновляем счетчик в меню "Плагины"
    foreach ($menu as $key => $item) {
        if (isset($item[2]) && $item[2] === 'plugins.php') {
            if ($missing_count > 0) {
                $menu[$key][0] = sprintf(
                    'Плагины <span class="update-plugins count-%d"><span class="plugin-count">%d</span></span>',
                    $missing_count,
                    $missing_count
                );
            }
            break;
        }
    }
}
add_action('admin_menu', 'theme_update_plugins_menu_counter', 100);

/**
 * Уведомление в админ-панели
 */
function theme_required_plugins_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'plugins_page_theme-required-plugins') {
        return;
    }
    
    $plugins_status = theme_get_plugins_status();
    $missing_required = array_filter($plugins_status, function($p) { 
        return $p['required'] && !$p['active']; 
    });
    
    if (!empty($missing_required) && current_user_can('install_plugins')) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong><?php echo esc_html__('Требуются плагины темы:', 'textdomain'); ?></strong> 
                <?php 
                printf(
                    esc_html__('Необходимо установить %d обязательных плагинов.', 'textdomain'),
                    count($missing_required)
                );
                ?>
                <a href="<?php echo esc_url(admin_url('plugins.php?page=theme-required-plugins')); ?>" class="button button-primary" style="margin-left: 10px;">
                    <?php echo esc_html__('Установить сейчас', 'textdomain'); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'theme_required_plugins_admin_notice');

/**
 * Хук для добавления своих плагинов в список необходимых
 */
add_filter('theme_required_plugins', function($plugins) {
    // Пример добавления плагина из внешнего источника
    /*
    $plugins['advanced-custom-fields'] = array(
        'name' => 'Advanced Custom Fields',
        'slug' => 'advanced-custom-fields',
        'file' => 'advanced-custom-fields/acf.php',
        'required' => true,
        'description' => 'Расширенное управление кастомными полями.',
        'source' => 'repo'
    );
    */
    
    return $plugins;
});