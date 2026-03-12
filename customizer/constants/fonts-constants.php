<?php
/*
 * Массив шрифтов для темы
 */
define('FONTS', array(
	'nunito' => 'Nunito',
	'mursgothic' => 'Murs Gothic',
	'arial' => 'Arial',
	'courier' => 'Courier New'
));


define('FONT_SIZE', 1);
define('FONT_SIZE_MOBILE', 1.1);

define('MAIN_FONT', 'nunito');
define('SECOND_FONT', 'mursgothic');
define('FONT_LINE_HEIGHT', 1.6);
define('TITLES_LINE_HEIGHT', 1.2);

/*
 * Функция для получения выбранного шрифта
 * @param string $mod Название theme_mod
 * @return string Выбранный шрифт
 * Пример: $titles_font = getSelectedFont('font_main');
 */
function getSelectedFont($mod)
{
	$font_family = '';
	switch (get_theme_mod($mod, MAIN_FONT)) {
		case 'mursgothic':
			$font_family = 'Murs Gothic;';
			break;
		case 'nunito':
			$font_family = 'Nunito;';
			break;
		case 'arial':
			$font_family = 'Arial, Helvetica, sans-serif;';
			break;
		case 'courier':
			$font_family = '"Courier New", Courier;';
			break;
		default:
			$font_family = 'Arial, Helvetica, sans-serif;';
			break;
	}

	return $font_family;
}
