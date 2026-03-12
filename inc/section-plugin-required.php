<?php
$plugins_status = theme_get_plugins_status();
$missing_required = array();
$missing_optional = array();

foreach ($plugins_status as $plugin) {
    if (!$plugin['active']) {
        if ($plugin['required']) {
            $missing_required[] = $plugin;
        } else {
            $missing_optional[] = $plugin;
        }
    }
}
?>

<div class="theme-required-plugins-notice">
    <div class="notice-container">
        <?php if (current_user_can('install_plugins')): ?>
            <div class="admin-notice-card">
                <h2>⚠️ <?php echo esc_html__('Требуется установка плагинов', 'textdomain'); ?></h2>
                <p class="description"><?php echo esc_html__('Для правильной работы темы необходимо установить и активировать следующие плагины:', 'textdomain'); ?></p>
                
                <?php if (!empty($missing_required)): ?>
                <div class="plugins-section-card">
                    <h3><span class="dashicons dashicons-warning" style="color: #f0b849;"></span> <?php echo esc_html__('Обязательные плагины', 'textdomain'); ?></h3>
                    <div class="plugins-table">
                        <?php foreach ($missing_required as $plugin): ?>
                        <div class="plugin-row required">
                            <div class="plugin-name">
                                <strong><?php echo esc_html($plugin['name']); ?></strong>
                            </div>
                            <div class="plugin-description">
                                <?php echo esc_html($plugin['description']); ?>
                            </div>
                            <div class="plugin-status">
                                <?php if (!$plugin['installed']): ?>
                                    <span class="plugin-status-badge status-not-installed"><?php echo esc_html__('Не установлен', 'textdomain'); ?></span>
                                <?php else: ?>
                                    <span class="plugin-status-badge status-inactive"><?php echo esc_html__('Не активен', 'textdomain'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($missing_optional)): ?>
                <div class="plugins-section-card">
                    <h3><span class="dashicons dashicons-star-filled" style="color: #f0f0f1;"></span> <?php echo esc_html__('Дополнительные плагины', 'textdomain'); ?></h3>
                    <div class="plugins-table">
                        <?php foreach ($missing_optional as $plugin): ?>
                        <div class="plugin-row optional">
                            <div class="plugin-name">
                                <strong><?php echo esc_html($plugin['name']); ?></strong>
                            </div>
                            <div class="plugin-description">
                                <?php echo esc_html($plugin['description']); ?>
                            </div>
                            <div class="plugin-status">
                                <?php if (!$plugin['installed']): ?>
                                    <span class="plugin-status-badge status-not-installed"><?php echo esc_html__('Не установлен', 'textdomain'); ?></span>
                                <?php else: ?>
                                    <span class="plugin-status-badge status-inactive"><?php echo esc_html__('Не активен', 'textdomain'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (empty($missing_required) && empty($missing_optional)): ?>
                    <div class="success-notice">
                        <p><span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span> <?php echo esc_html__('Все плагины установлены и активированы!', 'textdomain'); ?></p>
                        <a href="<?php echo esc_url(home_url()); ?>" class="button button-primary">
                            <?php echo esc_html__('Перейти на сайт', 'textdomain'); ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="action-section">
                        <p><?php echo esc_html__('Для установки плагинов перейдите в панель управления:', 'textdomain'); ?></p>
                        <a href="<?php echo esc_url(admin_url('plugins.php?page=theme-required-plugins')); ?>" class="button button-primary button-hero">
                            <span class="dashicons dashicons-admin-plugins"></span> <?php echo esc_html__('Установить плагины', 'textdomain'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="secondary-actions">
                    <a href="<?php echo esc_url(home_url()); ?>" class="button button-secondary">
                        <span class="dashicons dashicons-update"></span> <?php echo esc_html__('Обновить страницу', 'textdomain'); ?>
                    </a>
                    <a href="<?php echo esc_url(admin_url()); ?>" class="button button-secondary">
                        <span class="dashicons dashicons-dashboard"></span> <?php echo esc_html__('Панель управления', 'textdomain'); ?>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="user-notice-card">
                <h2><span class="dashicons dashicons-admin-tools"></span> <?php echo esc_html__('Сайт на техническом обслуживании', 'textdomain'); ?></h2>
                <p><?php echo esc_html__('В настоящее время проводятся работы по улучшению сайта. Пожалуйста, зайдите позже.', 'textdomain'); ?></p>
                <p class="admin-note"><small><?php echo esc_html__('Если вы администратор сайта, войдите в систему для установки необходимых компонентов.', 'textdomain'); ?></small></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.theme-required-plugins-notice {
    padding: 40px 20px;
    background: #f0f0f1;
    color: #1d2327;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.notice-container {
    max-width: 900px;
    width: 100%;
    margin: 0 auto;
}

.admin-notice-card,
.user-notice-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    padding: 30px;
}

.admin-notice-card h2 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #1d2327;
    font-size: 24px;
    font-weight: 600;
}

.admin-notice-card .description {
    color: #646970;
    margin-bottom: 30px;
    font-size: 14px;
    line-height: 1.5;
}

.plugins-section-card {
    margin: 25px 0;
    border: 1px solid #dcdcde;
    border-radius: 4px;
    overflow: hidden;
}

.plugins-section-card h3 {
    margin: 0;
    padding: 15px 20px;
    background: #f6f7f7;
    border-bottom: 1px solid #dcdcde;
    font-size: 16px;
    font-weight: 600;
    color: #1d2327;
}

.plugins-table {
    background: #fff;
}

.plugin-row {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 20px;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f1;
    align-items: center;
}

.plugin-row:last-child {
    border-bottom: none;
}

.plugin-row.required {
    background: #fff8e5;
}

.plugin-row.optional {
    background: #f8f9fa;
}

.plugin-name {
    font-weight: 600;
    color: #1d2327;
}

.plugin-description {
    color: #646970;
    font-size: 13px;
    line-height: 1.4;
}

.plugin-status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 2px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
}

.status-not-installed {
    background: #fff3cd;
    color: #856404;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.action-section {
    margin: 30px 0;
    padding: 20px;
    background: #f0f6fc;
    border: 1px solid #c5d9f1;
    border-radius: 4px;
    text-align: center;
}

.action-section p {
    margin: 0 0 15px 0;
    color: #1d2327;
    font-size: 14px;
}

.success-notice {
    margin: 30px 0;
    padding: 20px;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    text-align: center;
}

.success-notice p {
    margin: 0 0 15px 0;
    color: #155724;
    font-size: 16px;
    font-weight: 600;
}

.secondary-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #dcdcde;
    display: flex;
    gap: 10px;
    justify-content: center;
}

.user-notice-card {
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
}

.user-notice-card h2 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #1d2327;
}

.user-notice-card p {
    color: #646970;
    margin-bottom: 15px;
    line-height: 1.5;
}

.admin-note {
    color: #8c8f94 !important;
    font-style: italic;
}

/* Кнопки */
.theme-required-plugins-notice .button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.5;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
}

.theme-required-plugins-notice .button:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.theme-required-plugins-notice .button:active {
    transform: translateY(0);
    box-shadow: none;
}

.button-primary {
    background: #2271b1;
    color: #fff;
    border-color: #2271b1;
}

.button-primary:hover {
    background: #135e96;
    border-color: #135e96;
    color: #fff;
}

.button-secondary {
    background: #f6f7f7;
    color: #3c434a;
    border-color: #c3c4c7;
}

.button-secondary:hover {
    background: #f0f0f1;
    border-color: #8c8f94;
    color: #3c434a;
}

.button-hero {
    padding: 12px 24px;
    font-size: 16px;
    font-weight: 600;
}

.button .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Адаптивность */
@media screen and (max-width: 782px) {
    .theme-required-plugins-notice {
        padding: 20px 15px;
    }
    
    .admin-notice-card,
    .user-notice-card {
        padding: 20px;
    }
    
    .plugin-row {
        grid-template-columns: 1fr;
        gap: 10px;
        text-align: center;
    }
    
    .secondary-actions {
        flex-direction: column;
    }
    
    .secondary-actions .button {
        width: 100%;
        justify-content: center;
    }
    
    .admin-notice-card h2 {
        font-size: 20px;
    }
}

@media screen and (max-width: 600px) {
    .admin-notice-card h2 {
        font-size: 18px;
    }
    
    .plugins-section-card h3 {
        font-size: 14px;
        padding: 12px 15px;
    }
    
    .plugin-name,
    .plugin-description {
        font-size: 12px;
    }
    
    .button-hero {
        padding: 10px 20px;
        font-size: 14px;
    }
}
</style>