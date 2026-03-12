(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { useState, createElement: el, Fragment } = wp.element;
    const { useSelect, useDispatch } = wp.data;
    const { SelectControl, Button, ToolbarGroup, ToolbarButton, PanelBody, PanelRow, BaseControl, FormTokenField } = wp.components;
    const { useBlockProps, BlockControls, InspectorControls, MediaUpload } = wp.blockEditor;

    const WorksCategoriesEdit = function ({ attributes, setAttributes, clientId }) {
        const { categories = [] } = attributes;
        const blockProps = useBlockProps();

        // Получаем все категории works_category
        const allCategories = useSelect((select) => {
            const terms = select('core').getEntityRecords('taxonomy', 'works_category', { per_page: -1 });
            return terms || [];
        }, []);

        // Получаем медиа объекты для изображений
        const mediaItems = useSelect(
            (select) => {
                const media = {};
                categories.forEach((cat) => {
                    if (cat.imageId) {
                        const item = select('core').getMedia(cat.imageId);
                        if (item) {
                            media[cat.imageId] = item.source_url;
                        }
                    }
                });
                return media;
            },
            [categories]
        );

        const categorySuggestions = allCategories.map((cat) => cat.name);

        // Выбранные названия категорий (для FormTokenField)
        const selectedNames = categories.map((cat) => cat.name);

        const handleCategoryChange = (tokens) => {
            // tokens - массив названий категорий
            // Находим ID по названию
            const newCategories = [];
            tokens.forEach((token) => {
                // Ищем категорию по имени (точное совпадение)
                const term = allCategories.find((cat) => cat.name === token);
                if (term) {
                    // Проверяем, уже ли добавлена
                    const existing = newCategories.find((c) => c.id === term.id);
                    if (!existing) {
                        newCategories.push({
                            id: term.id,
                            name: term.name,
                            imageId: categories.find((c) => c.id === term.id)?.imageId || 0,
                        });
                    }
                }
            });
            // Удаляем те, которых нет в tokens
            setAttributes({ categories: newCategories });
        };

        const updateCategoryImage = (termId, imageId) => {
            const updated = categories.map((cat) => {
                if (cat.id === termId) {
                    return { ...cat, imageId };
                }
                return cat;
            });
            setAttributes({ categories: updated });
        };

        const removeCategoryImage = (termId) => {
            updateCategoryImage(termId, 0);
        };

        const removeCategory = (termId) => {
            const updated = categories.filter((cat) => cat.id !== termId);
            setAttributes({ categories: updated });
        };

        const { removeBlock } = useDispatch('core/block-editor');
        const handleDelete = () => {
            if (clientId) {
                removeBlock(clientId);
            }
        };

        return el(
            Fragment,
            null,
            el(
                BlockControls,
                { key: 'controls' },
                el(
                    ToolbarGroup,
                    {},
                    el(ToolbarButton, {
                        icon: 'trash',
                        label: __('Удалить блок', 'sws'),
                        onClick: handleDelete,
                    })
                )
            ),
            el(
                InspectorControls,
                { key: 'inspector' },
                el(
                    PanelBody,
                    { title: __('Настройки категорий', 'sws'), initialOpen: true },
                    el(FormTokenField, {
                        label: __('Выберите категории', 'sws'),
                        value: selectedNames,
                        suggestions: categorySuggestions,
                        onChange: handleCategoryChange,
                        placeholder: __('Введите название категории', 'sws'),
                        maxSuggestions: 10,
                    }),
                    categories.map((cat) => {
                        const imageUrl = cat.imageId ? mediaItems[cat.imageId] : null;
                        return el(
                            PanelRow,
                            { key: cat.id },
                            el(
                                BaseControl,
                                { label: cat.name },
                                el(
                                    'div',
                                    { style: { display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '10px' } },
                                    el(MediaUpload, {
                                        onSelect: (media) => updateCategoryImage(cat.id, media.id),
                                        allowedTypes: ['image'],
                                        render: ({ open }) =>
                                            el(
                                                Button,
                                                {
                                                    onClick: open,
                                                    isSecondary: true,
                                                    icon: 'format-image',
                                                },
                                                cat.imageId ? __('Заменить изображение', 'sws') : __('Добавить изображение', 'sws')
                                            ),
                                    }),
                                    imageUrl &&
                                        el(
                                            'div',
                                            { style: { position: 'relative' } },
                                            el('img', {
                                                src: imageUrl,
                                                style: { width: '50px', height: '50px', objectFit: 'cover' },
                                                alt: '',
                                            }),
                                            el(Button, {
                                                icon: 'no',
                                                label: __('Удалить изображение', 'sws'),
                                                onClick: () => removeCategoryImage(cat.id),
                                                isSmall: true,
                                                isDestructive: true,
                                                style: { position: 'absolute', top: '-5px', right: '-5px' },
                                            })
                                        ),
                                    el(Button, {
                                        icon: 'remove',
                                        label: __('Удалить категорию', 'sws'),
                                        onClick: () => removeCategory(cat.id),
                                        isDestructive: true,
                                    })
                                )
                            )
                        );
                    })
                )
            ),
            el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'worksCategories' },
                    categories.length === 0
                        ? el('p', {}, __('Выберите категории в панели настроек.', 'sws'))
                        : categories.map((cat) => {
                              const imageUrl = cat.imageId ? mediaItems[cat.imageId] : null;
                              return el(
                                  'div',
                                  { key: cat.id, className: 'worksCategories__item' },
                                  imageUrl &&
                                      el('img', {
                                          src: imageUrl,
                                          alt: cat.name,
                                          className: 'worksCategories__image',
                                      }),
                                  el('h3', { className: 'worksCategories__title' }, cat.name),
                                  el('a', { href: '#', className: 'worksCategories__link' }, __('', 'sws'))
                              );
                          })
                )
            )
        );
    };

    wp.blocks.registerBlockType('sws/works-categories', {
        edit: WorksCategoriesEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);
