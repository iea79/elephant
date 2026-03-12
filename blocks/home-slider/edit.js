(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { useState, createElement: el, Fragment } = wp.element;
    const { useSelect, useDispatch } = wp.data;
    const {
        Button,
        ToolbarGroup,
        ToolbarButton,
        PanelBody,
        PanelRow,
        BaseControl,
        ToggleControl,
        RangeControl,
        RadioControl,
    } = wp.components;
    const { useBlockProps, BlockControls, InspectorControls, MediaUpload } = wp.blockEditor;

    const HomeSliderEdit = function ({ attributes, setAttributes, clientId }) {
        const { images = [], autoplay = true, autoplaySpeed = 3000, dots = true, arrows = false, fade = false, speed = 500 } = attributes;
        const blockProps = useBlockProps();

        // Получаем медиа объекты для изображений
        const mediaItems = useSelect(
            (select) => {
                const media = {};
                images.forEach((imgId) => {
                    if (imgId) {
                        const item = select('core').getMedia(imgId);
                        if (item) {
                            media[imgId] = item.source_url;
                        }
                    }
                });
                return media;
            },
            [images]
        );

        const handleAddImages = (newImages) => {
            const newIds = newImages.map((img) => img.id);
            setAttributes({ images: [...images, ...newIds] });
        };

        const handleRemoveImage = (id) => {
            setAttributes({ images: images.filter((imgId) => imgId !== id) });
        };

        const handleReorder = (fromIndex, toIndex) => {
            const newImages = [...images];
            const [removed] = newImages.splice(fromIndex, 1);
            newImages.splice(toIndex, 0, removed);
            setAttributes({ images: newImages });
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
                    { title: __('Настройки галереи', 'sws'), initialOpen: true },
                    el(MediaUpload, {
                        onSelect: handleAddImages,
                        allowedTypes: ['image'],
                        multiple: true,
                        gallery: true,
                        render: ({ open }) =>
                            el(
                                Button,
                                {
                                    onClick: open,
                                    isPrimary: true,
                                    icon: 'upload',
                                },
                                __('Добавить изображения', 'sws')
                            ),
                    }),
                    images.length > 0 &&
                        el(
                            'div',
                            { style: { marginTop: '15px' } },
                            el('p', { style: { fontWeight: 'bold' } }, __('Выбранные изображения:', 'sws')),
                            images.map((imgId, index) => {
                                const imageUrl = mediaItems[imgId] || '';
                                return el(
                                    'div',
                                    {
                                        key: imgId,
                                        style: {
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '10px',
                                            marginBottom: '10px',
                                            padding: '10px',
                                            border: '1px solid #ddd',
                                            borderRadius: '4px',
                                        },
                                    },
                                        el('img', {
                                            src: imageUrl,
                                            style: { width: '60px', height: '60px', objectFit: 'cover' },
                                            alt: '',
                                        }),
                                    el('span', { style: { flex: 1 } }, `ID: ${imgId}`),
                                    el(Button, {
                                        icon: 'arrow-up',
                                        label: __('Вверх', 'sws'),
                                        onClick: () => handleReorder(index, index - 1),
                                        disabled: index === 0,
                                        isSmall: true,
                                    }),
                                    el(Button, {
                                        icon: 'arrow-down',
                                        label: __('Вниз', 'sws'),
                                        onClick: () => handleReorder(index, index + 1),
                                        disabled: index === images.length - 1,
                                        isSmall: true,
                                    }),
                                    el(Button, {
                                        icon: 'no',
                                        label: __('Удалить', 'sws'),
                                        onClick: () => handleRemoveImage(imgId),
                                        isDestructive: true,
                                        isSmall: true,
                                    })
                                );
                            })
                        )
                ),
                el(
                    PanelBody,
                    { title: __('Настройки слайдера', 'sws'), initialOpen: false },
                    el(ToggleControl, {
                        label: __('Автоплей', 'sws'),
                        checked: autoplay,
                        onChange: (value) => setAttributes({ autoplay: value }),
                    }),
                    autoplay &&
                        el(RangeControl, {
                            label: __('Скорость автоплея (мс)', 'sws'),
                            value: autoplaySpeed,
                            onChange: (value) => setAttributes({ autoplaySpeed: value }),
                            min: 500,
                            max: 10000,
                            step: 100,
                        }),
                    el(ToggleControl, {
                        label: __('Показывать точки (дотсы)', 'sws'),
                        checked: dots,
                        onChange: (value) => setAttributes({ dots: value }),
                    }),
                    el(ToggleControl, {
                        label: __('Показывать стрелки', 'sws'),
                        checked: arrows,
                        onChange: (value) => setAttributes({ arrows: value }),
                    }),
                    el(ToggleControl, {
                        label: __('Эффект fade', 'sws'),
                        checked: fade,
                        onChange: (value) => setAttributes({ fade: value }),
                    }),
                    el(RangeControl, {
                        label: __('Скорость перехода (мс)', 'sws'),
                        value: speed,
                        onChange: (value) => setAttributes({ speed: value }),
                        min: 100,
                        max: 2000,
                        step: 100,
                    })
                )
            ),
            el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'homeSlider', style: { border: '1px dashed #ccc', padding: '20px', textAlign: 'center' } },
                    images.length === 0
                        ? el('p', {}, __('Добавьте изображения в настройках блока.', 'sws'))
                        : el(
                              'div',
                              { className: 'homeSlider__preview' },
                              images.map((imgId, index) => {
                                  const imageUrl = mediaItems[imgId] || '';
                                  return el('img', {
                                      key: imgId,
                                      src: imageUrl,
                                      alt: __('Изображение слайда', 'sws'),
                                      style: {
                                          maxWidth: '100%',
                                          height: 'auto',
                                          marginBottom: '10px',
                                          display: index === 0 ? 'block' : 'none',
                                      },
                                  });
                              }),
                              el('p', { style: { fontSize: '14px', color: '#666' } }, __('Предпросмотр слайдера (только первое изображение). На фронте будет работать слайдер.', 'sws'))
                          )
                )
            )
        );
    };

    wp.blocks.registerBlockType('sws/home-slider', {
        edit: HomeSliderEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);