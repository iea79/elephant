(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { createElement: el, Fragment } = wp.element;
    const { useSelect } = wp.data;
    const { Button, PanelBody } = wp.components;
    const { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor || wp.editor;

    const WorkSingleGalleryEdit = function ({ attributes, setAttributes }) {
        const { images = [] } = attributes;
        const blockProps = useBlockProps ? useBlockProps() : {};

        const mediaItems = useSelect(
            (select) => {
                const media = {};
                images.forEach((img) => {
                    if (img && img.id) {
                        const item = select('core').getMedia(img.id);
                        if (item) {
                            media[img.id] = item;
                        }
                    }
                });
                return media;
            },
            [images],
        );

        const onSelectImages = (newImages) => {
            const prepared = newImages.map((img) => ({
                id: img.id,
                url: img.url || (img.sizes && img.sizes.large ? img.sizes.large.url : img.source_url),
                thumb: img.sizes && img.sizes.thumbnail ? img.sizes.thumbnail.url : img.url || img.source_url,
            }));
            setAttributes({ images: prepared });
        };

        const removeImage = (indexToRemove) => {
            setAttributes({
                images: images.filter((_, index) => index !== indexToRemove),
            });
        };

        const moveImage = (from, to) => {
            if (to < 0 || to >= images.length) {
                return;
            }
            const updated = [...images];
            const [moved] = updated.splice(from, 1);
            updated.splice(to, 0, moved);
            setAttributes({ images: updated });
        };

        // Стили только для превью в редакторе, без правок editor.css
        const previewStyles = {
            wrapper: {
                border: '1px dashed #ddd',
                padding: '1px',
            },
            mainWrapper: {
                marginBottom: '8px',
            },
            mainImage: {
                width: '100%',
                height: '36em',
                display: 'block',
                borderRadius: '4px',
                objectFit: 'cover',
            },
            thumbs: {
                display: 'flex',
                gap: '6px',
                flexWrap: 'wrap',
            },
            thumbButton: (isActive) => ({
                border: isActive ? '2px solid #beb3a5' : '1px solid #ddd',
                padding: 0,
                background: '#fff',
                cursor: 'default',
                opacity: isActive ? 1 : 0.7,
                borderRadius: '2px',
            }),
            thumbImage: {
                width: '56px',
                height: '56px',
                display: 'block',
                objectFit: 'cover',
                borderRadius: '2px',
            },
        };

        return el(
            Fragment,
            null,
            el(
                InspectorControls,
                { key: 'inspector' },
                el(
                    PanelBody,
                    { title: __('Изображения галереи', 'sws'), initialOpen: true },
                    el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                            onSelect: onSelectImages,
                            allowedTypes: ['image'],
                            multiple: true,
                            gallery: true,
                            value: images.map((img) => img.id),
                            render: ({ open }) =>
                                el(
                                    Button,
                                    {
                                        onClick: open,
                                        isPrimary: true,
                                    },
                                    images.length ? __('Изменить изображения', 'sws') : __('Выбрать изображения', 'sws'),
                                ),
                        }),
                    ),
                    images.length > 0 &&
                        el(
                            'div',
                            { style: { marginTop: '15px' } },
                            el(
                                'div',
                                {
                                    style: {
                                        display: 'grid',
                                        gridTemplateColumns: 'repeat(auto-fill, minmax(80px, 1fr))',
                                        gap: '10px',
                                    },
                                },
                                images.map((img, index) => {
                                    const media = img.id ? mediaItems[img.id] : null;
                                    const url = img.thumb || img.url || (media && media.source_url) || '';
                                    return el(
                                        'div',
                                        {
                                            key: index,
                                            style: {
                                                position: 'relative',
                                                border: '1px solid #ddd',
                                                borderRadius: '4px',
                                                overflow: 'hidden',
                                            },
                                        },
                                        url &&
                                            el('img', {
                                                src: url,
                                                alt: '',
                                                style: {
                                                    width: '100%',
                                                    height: '80px',
                                                    objectFit: 'cover',
                                                    display: 'block',
                                                },
                                            }),
                                        el(
                                            'div',
                                            {
                                                style: {
                                                    position: 'absolute',
                                                    top: '4px',
                                                    right: '4px',
                                                    display: 'flex',
                                                    flexDirection: 'column',
                                                    gap: '2px',
                                                },
                                            },
                                            el(Button, {
                                                icon: 'no-alt',
                                                label: __('Удалить', 'sws'),
                                                onClick: () => removeImage(index),
                                                isSmall: true,
                                                isSecondary: true,
                                            }),
                                            el(Button, {
                                                icon: 'arrow-up',
                                                label: __('Вверх', 'sws'),
                                                onClick: () => moveImage(index, index - 1),
                                                isSmall: true,
                                                disabled: index === 0,
                                            }),
                                            el(Button, {
                                                icon: 'arrow-down',
                                                label: __('Вниз', 'sws'),
                                                onClick: () => moveImage(index, index + 1),
                                                isSmall: true,
                                                disabled: index === images.length - 1,
                                            }),
                                        ),
                                    );
                                }),
                            ),
                        ),
                ),
            ),
            el(
                'div',
                Object.assign({}, blockProps, {
                    className: (blockProps.className || '') + ' workSingle__gallery',
                    style: Object.assign({}, blockProps.style || {}, previewStyles.wrapper),
                }),
                images.length === 0
                    ? el('p', {}, __('Выберите изображения для галереи.', 'sws'))
                    : el(
                          Fragment,
                          null,
                          // Основное изображение (первое)
                          el(
                              'div',
                              {
                                  className: 'workSingle__sliderWrapper',
                                  style: previewStyles.mainWrapper,
                              },
                              (() => {
                                  const first = images[0];
                                  const media = first && first.id ? mediaItems[first.id] : null;
                                  const url = (first && first.url) || (media && media.source_url) || (first && first.thumb) || '';
                                  if (!url) {
                                      return el('p', {}, __('Не удалось загрузить изображение.', 'sws'));
                                  }
                                  return el(
                                      'div',
                                      { className: 'workSingle__slide' },
                                      el('img', {
                                          src: url,
                                          alt: '',
                                          style: previewStyles.mainImage,
                                      }),
                                  );
                              })(),
                          ),
                          // Миниатюры
                          images.length > 1 &&
                              el(
                                  'div',
                                  {
                                      className: 'workSingle__thumbs',
                                      style: previewStyles.thumbs,
                                  },
                                  images.map((img, index) => {
                                      const media = img.id ? mediaItems[img.id] : null;
                                      const url = img.thumb || img.url || (media && media.source_url) || '';
                                      if (!url) {
                                          return null;
                                      }
                                      const isActive = index === 0;
                                      return el(
                                          'button',
                                          {
                                              key: index,
                                              type: 'button',
                                              className: 'workSingle__thumb' + (isActive ? ' is-active' : ''),
                                              disabled: true,
                                              style: previewStyles.thumbButton(isActive),
                                          },
                                          el('img', {
                                              src: url,
                                              alt: '',
                                              style: previewStyles.thumbImage,
                                          }),
                                      );
                                  }),
                              ),
                      ),
            ),
        );
    };

    wp.blocks.registerBlockType('sws/work-single-gallery', {
        edit: WorkSingleGalleryEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);
