(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { createElement: el, Fragment } = wp.element;
    const { useSelect, useDispatch } = wp.data;
    const { Button, ToolbarGroup, ToolbarButton, TextControl, TextareaControl, PanelBody } = wp.components;
    const { useBlockProps, BlockControls, InspectorControls, MediaUpload } = wp.blockEditor;

    const StepsEdit = function ({ attributes, setAttributes, clientId }) {
        const { steps = [] } = attributes;
        const blockProps = useBlockProps();

        const mediaItems = useSelect(
            (select) => {
                const media = {};
                steps.forEach((step) => {
                    if (step.imageId) {
                        const item = select('core').getMedia(step.imageId);
                        if (item) {
                            media[step.imageId] = item.source_url;
                        }
                    }
                });
                return media;
            },
            [steps]
        );

        const updateStep = (index, newData) => {
            const newSteps = steps.map((step, i) => (i === index ? { ...step, ...newData } : step));
            setAttributes({ steps: newSteps });
        };

        const addStep = () => {
            setAttributes({
                steps: [
                    ...steps,
                    {
                        imageId: 0,
                        title: '',
                        text: '',
                    },
                ],
            });
        };

        const removeStep = (index) => {
            const newSteps = steps.filter((_, i) => i !== index);
            setAttributes({ steps: newSteps });
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
                    { title: __('Настройки шагов', 'sws'), initialOpen: true },
                    el(
                        Button,
                        {
                            isSecondary: true,
                            onClick: addStep,
                        },
                        __('Добавить шаг', 'sws')
                    )
                )
            ),
            el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'steps' },
                    steps.length === 0
                        ? el('p', {}, __('Добавьте шаги через панель настроек блока.', 'sws'))
                        : steps.map((step, index) => {
                              const imageUrl = step.imageId ? mediaItems[step.imageId] : null;
                              return el(
                                  'div',
                                  { key: index, className: 'steps__item' },
                                  el(
                                      'div',
                                      { className: 'steps__imageWrapper' },
                                      imageUrl &&
                                          el('img', {
                                              src: imageUrl,
                                              alt: step.title || '',
                                              className: 'steps__image',
                                          }),
                                      el(MediaUpload, {
                                          onSelect: (media) =>
                                              updateStep(index, {
                                                  imageId: media.id,
                                              }),
                                          allowedTypes: ['image'],
                                          render: ({ open }) =>
                                              el(
                                                  Button,
                                                  {
                                                      onClick: open,
                                                      isSecondary: true,
                                                  },
                                                  step.imageId ? __('Заменить картинку', 'sws') : __('Добавить картинку', 'sws')
                                              ),
                                      })
                                  ),
                                  el(TextControl, {
                                      label: false,
                                      value: step.title || '',
                                      style: { backgroundColor: 'transparent', border: '0' },
                                      className: 'steps__title',
                                      placeholder: __('Название шага', 'sws'),
                                      onChange: (value) =>
                                          updateStep(index, {
                                              title: value,
                                          }),
                                  }),
                                  el(TextareaControl, {
                                      label: false,
                                      value: step.text || '',
                                      style: { backgroundColor: 'transparent', border: '0' },
                                      placeholder: __('Текст шага', 'sws'),
                                      className: 'steps__text',
                                      onChange: (value) =>
                                          updateStep(index, {
                                              text: value,
                                          }),
                                  }),
                                  el(
                                      Button,
                                      {
                                          isDestructive: true,
                                          isSecondary: true,
                                          onClick: () => removeStep(index),
                                          style: { position: 'absolute', top: '10px', right: '10px', zIndex: 100 },
                                      },
                                      __('X', 'sws')
                                  )
                              );
                          }),
                    el(
                        Button,
                        {
                            isPrimary: true,
                            onClick: addStep,
                            style: {
                                width: '30px',
                                height: '30px',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                fontSize: '22px',
                                position: 'absolute',
                                bottom: '30px',
                                right: '0',
                                zIndex: 100,
                            },
                        },
                        __('+', 'sws')
                    )
                )
            )
        );
    };

    wp.blocks.registerBlockType('sws/steps', {
        edit: StepsEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);
