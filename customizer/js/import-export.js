jQuery(document).ready(function ($) {
    // Обработчик кнопки экспорта
    $(document).on('click', '.export_button', function (e) {
        e.preventDefault();
        console.log('export_button clicked');

        // Отправляем запрос на экспорт
        $.ajax({
            url: sws_import_export.ajax_url,
            type: 'POST',
            data: {
                action: 'sws_export_customizer_settings',
                sws_export_nonce: sws_import_export.nonce,
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    // Показываем сообщение об успешном экспорте
                    alert(response.data.message);

                    // Если в ответе есть ссылка на файл, переходим по ней для скачивания
                    if (response.data.download_url) {
                        window.location.href = response.data.download_url;
                    }
                } else {
                    alert('Ошибка при сохранении данных: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr, status, error);
                alert('Ошибка при сохранении данных: ' + xhr.responseText);
            },
        });
    });

    // Обработчик кнопки импорта
    $(document).on('click', '.import_button', function (e) {
        e.preventDefault();
        let imported = false;

        // Выводим сообщение о том, что в процессе весь контент будет удален, Да - Нет
        if (confirm('Вы уверены, что хотите удалить все данные и загрузить новые?')) {
            imported = true;

            // Добавляем прелоадер с блокировкой контента в #customize-preview
            $('#customize-preview').append('<div class="customize-loader"><h3><span class="loader"></span></div>');

            // Создаем FormData для отправки файла
            let formData = new FormData();
            formData.append('action', 'sws_import_customizer_settings');
            formData.append('sws_import_nonce', sws_import_export.nonce);

            // button disabled="disabled"
            toggleUiDisabled(imported);

            // Отправляем запрос на импорт
            $.ajax({
                url: sws_import_export.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    imported = false;
                    toggleUiDisabled(imported);
                    console.log(response);

                    if (response.success) {
                        alert(response.data.message);
                        // Перезагружаем страницу для применения изменений
                        location.reload();
                    } else {
                        // Проверяем, есть ли детальное сообщение об ошибке
                        if (response.data && response.data.message) {
                            alert('Ошибка при импорте данных: ' + response.data.message);
                        } else {
                            alert('Ошибка при импорте данных: Неизвестная ошибка');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr, status, error);
                    imported = false;
                    toggleUiDisabled(imported);

                    // Пытаемся распарсить JSON ответ с ошибкой
                    try {
                        let errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.data && errorResponse.data.message) {
                            alert('Ошибка при импорте данных: ' + errorResponse.data.message);
                        } else {
                            alert('Ошибка при импорте данных: ' + xhr.responseText);
                        }
                    } catch (e) {
                        alert('Ошибка при импорте данных: ' + xhr.responseText);
                    }
                    console.log('Ошибка при импорте данных: ', xhr.responseText);
                },
            });
        } else {
            imported = false;
            toggleUiDisabled(imported);
        }

        onbeforeunload = function (e) {
            if (imported) {
                e.returnValue = 'Вы уверены, что хотите покинуть страницу? Процесс импорта будет прерван.';
            }
        };
    });

    function toggleUiDisabled(proccess) {
        if (proccess) {
            // Добавляем прелоадер с блокировкой контента в #customize-preview
            $('#customize-preview').append('<div class="customize-loader"><h3><span class="loader"></span></div>');

            $('.customize-section-back').attr('disabled', 'disabled');
            $('.export_button').attr('disabled', 'disabled');
            $('.import_button').attr('disabled', 'disabled');
        } else {
            $('.customize-section-back').removeAttr('disabled');
            $('.export_button').removeAttr('disabled');
            $('.import_button').removeAttr('disabled');
            $('.customize-loader').remove();
        }
    }
});
