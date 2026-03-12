(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { createElement: el, Fragment } = wp.element;
    const { useSelect } = wp.data;
    const {
        PanelBody,
        TextControl,
        RangeControl,
        Button,
    } = wp.components;
    const {
        useBlockProps,
        InspectorControls,
        MediaUpload,
        MediaUploadCheck,
    } = wp.blockEditor || wp.editor;

    const YandexMapEdit = function ({ attributes, setAttributes }) {
        const { address = '', markerId = 0, zoom = 16, height = 360, apiKey = '' } = attributes;
        const blockProps = useBlockProps ? useBlockProps() : {};

        const markerMedia = useSelect(
            (select) => {
                if (!markerId) {
                    return null;
                }
                return select('core').getMedia(markerId);
            },
            [markerId]
        );

        const markerUrl =
            (markerMedia && markerMedia.source_url) ||
            '';

        const previewStyles = {
            wrapper: {
                border: '1px dashed #ddd',
                padding: '12px',
                borderRadius: '4px',
                background: '#f7f7f7',
            },
            mapBox: {
                width: '100%',
                height: height + 'px',
                borderRadius: '4px',
                background: '#e5e3df',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'relative',
                overflow: 'hidden',
            },
            marker: {
                position: 'absolute',
                width: '36px',
                height: '36px',
                borderRadius: '50%',
                background: '#284a42',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: '#fff',
                fontSize: '16px',
            },
            markerImage: {
                maxWidth: '100%',
                maxHeight: '100%',
                objectFit: 'contain',
            },
            addressText: {
                position: 'absolute',
                bottom: '8px',
                left: '8px',
                right: '8px',
                padding: '6px 8px',
                background: 'rgba(0,0,0,0.45)',
                color: '#fff',
                fontSize: '12px',
                borderRadius: '3px',
            },
            emptyText: {
                fontSize: '13px',
                color: '#666',
                textAlign: 'center',
                padding: '24px 0',
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
                    { title: __('Настройки карты', 'sws'), initialOpen: true },
                    el(TextControl, {
                        label: __('Адрес', 'sws'),
                        help: __('Введите адрес, по которому нужно построить карту.', 'sws'),
                        value: address,
                        onChange: (value) => setAttributes({ address: value }),
                    }),
                    el(RangeControl, {
                        label: __('Масштаб (zoom)', 'sws'),
                        value: zoom,
                        onChange: (value) => setAttributes({ zoom: value }),
                        min: 5,
                        max: 19,
                    }),
                    el(RangeControl, {
                        label: __('Высота карты (px)', 'sws'),
                        value: height,
                        onChange: (value) => setAttributes({ height: value }),
                        min: 200,
                        max: 800,
                        step: 20,
                    }),
                    el(TextControl, {
                        label: __('Yandex Maps API key', 'sws'),
                        help: __('Если указать ключ здесь, он будет использован для загрузки API-карт.', 'sws'),
                        value: apiKey,
                        onChange: (value) => setAttributes({ apiKey: value }),
                    })
                ),
                el(
                    PanelBody,
                    { title: __('Маркер', 'sws'), initialOpen: false },
                    el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                            onSelect: (media) => {
                                setAttributes({ markerId: media ? media.id : 0 });
                            },
                            value: markerId || undefined,
                            allowedTypes: ['image'],
                            render: ({ open }) =>
                                el(
                                    Button,
                                    {
                                        onClick: open,
                                        isSecondary: true,
                                    },
                                    markerId
                                        ? __('Изменить иконку маркера', 'sws')
                                        : __('Выбрать иконку маркера', 'sws')
                                ),
                        })
                    ),
                    markerId &&
                        markerUrl &&
                        el(
                            'div',
                            {
                                style: {
                                    marginTop: '10px',
                                    display: 'inline-block',
                                    border: '1px solid #ddd',
                                    padding: '4px',
                                    borderRadius: '4px',
                                },
                            },
                            el('img', {
                                src: markerUrl,
                                alt: '',
                                style: { maxWidth: '64px', height: 'auto' },
                            })
                        ),
                    markerId &&
                        el(
                            Button,
                            {
                                isLink: true,
                                isDestructive: true,
                                style: { marginTop: '8px', display: 'block' },
                                onClick: () => setAttributes({ markerId: 0 }),
                            },
                            __('Сбросить маркер (использовать стандартный)', 'sws')
                        )
                )
            ),
            el(
                'div',
                Object.assign({}, blockProps, {
                    className: (blockProps.className || '') + ' swsYandexMap-editorPreview',
                    style: Object.assign({}, blockProps.style || {}, previewStyles.wrapper),
                }),
                !address
                    ? el(
                          'div',
                          { style: previewStyles.emptyText },
                          __('Укажите адрес в настройках блока, чтобы отобразить карту.', 'sws')
                      )
                    : el(
                          'div',
                          { style: previewStyles.mapBox },
                          el(
                              'div',
                              {
                                  style: Object.assign({}, previewStyles.marker, {
                                      top: '50%',
                                      left: '50%',
                                      transform: 'translate(-50%, -50%)',
                                  }),
                              },
                              markerId && markerUrl
                                  ? el('img', {
                                        src: markerUrl,
                                        alt: '',
                                        style: previewStyles.markerImage,
                                    })
                                  : '•'
                          ),
                          el(
                              'div',
                              { style: previewStyles.addressText },
                              address
                          )
                      )
            )
        );
    };

    wp.blocks.registerBlockType('sws/yandex-map', {
        edit: YandexMapEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);

