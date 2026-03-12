(function (wp) {
    const { __ } = wp.i18n || { __: (text) => text };
    const { useState, createElement: el, Fragment } = wp.element;
    const { useSelect, useDispatch } = wp.data;
    const { Button, ComboboxControl, ToolbarGroup, ToolbarButton } = wp.components;
    const { useBlockProps, BlockControls, InspectorControls } = wp.blockEditor;

    const PostSelectorEdit = function ({ attributes, setAttributes, clientId }) {
        const { selectedPostId, placeholder } = attributes;
        const [posts, setPosts] = useState([]);

        const isSelected = useSelect(
            function (select) {
                return select('core/block-editor').getSelectedBlockClientId() === clientId;
            },
            [clientId]
        );

        const blockProps = useBlockProps({
            className: 'objectSelector' + (isSelected ? ' is-selected' : ''),
        });

        const options = posts.map(function (post) {
            return {
                label: post.title.rendered,
                value: post.id,
            };
        });

        const handleButtonClick = function () {
            if (selectedPostId) {
                // In editor we can't navigate, just show alert
                alert(__('В редакторе переход невозможен. На фронтенде будет ссылка.', ''));
            }
        };

        const { removeBlock } = useDispatch('core/block-editor');
        const handleDelete = function () {
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
            // el(
            //     InspectorControls,
            //     { key: 'inspector' },
            //     el(
            //         'div',
            //         { className: 'sws-post-selector-settings' },
            //         el(TextControl, {
            //             label: __('Текст кнопки', 'sws'),
            //             value: buttonLabel,
            //             onChange: function (newLabel) {
            //                 setAttributes({ buttonLabel: newLabel });
            //             },
            //         }),
            //         el(TextControl, {
            //             label: __('Плейсхолдер', 'sws'),
            //             value: placeholder,
            //             onChange: function (newPlaceholder) {
            //                 setAttributes({ placeholder: newPlaceholder });
            //             },
            //         })
            //     )
            // ),
            el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'objectSelector' },
                    el(
                        'div',
                        { className: 'objectSelector__input_wrapper' },
                        el(ComboboxControl, {
                            label: false,
                            value: '',
                            options: options,
                            placeholder: placeholder,
                            className: 'objectSelector__input',
                        })
                    ),
                    el(Button, {
                        className: 'ie-icon_arrow objectSelector__button btn',
                        onClick: handleButtonClick,
                    })
                )
            )
        );
    };

    wp.blocks.registerBlockType('sws/post-selector', {
        edit: PostSelectorEdit,
        save: function () {
            return null;
        },
    });
})(window.wp);
