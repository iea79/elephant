jQuery(document).ready(function ($) {
    // Функция для добавления отображения значения к range контроллам
    function addRangeValueDisplay() {
        // Находим все range контроллы в кастомайзере
        $('.customize-control-range input[type="range"]').each(function () {
            var $range = $(this);
            var $control = $range.closest('.customize-control');

            // Проверяем, существует ли уже обертка
            if (!$range.parent().hasClass('customize-control-range-wrap')) {
                // Создаем обертку и добавляем в нее инпут и элемент отображения значения
                var value = $range.val();
                var $wrapper = $('<div class="customize-control-range-wrap" style="display: flex; align-items: center;"></div>');
                var $valueDisplay = $('<span class="range-value-display" style="margin-left: 10px; font-weight: bold;">' + value + '</span>');

                // Оборачиваем инпут и добавляем элемент отображения значения
                $range.wrap($wrapper);
                $range.after($valueDisplay);
            }

            // Обновляем значение при изменении
            $range.on('input', function () {
                $range.next('.range-value-display').text($(this).val());
            });
        });
    }

    // Запускаем функцию при добавлении новых контролов
    $(document).on('expanded', '.control-section', function () {
        setTimeout(addRangeValueDisplay, 100);
    });
});
