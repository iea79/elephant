<?php

class Smart_Custom_Fields_Field_Media extends Smart_Custom_Fields_Field_Base
{
	/**
	 * Set the required items.
	 *
	 * @return array
	 */
	protected function init()
	{
		return array(
			'type'         => 'media',
			'display-name' => __('Изображение или видео', 'smart-custom-fields'),
			'optgroup'     => 'other-fields',
			'allow-multiple-data' => true,
		);
	}

	/**
	 * Set the non required items.
	 *
	 * @return array
	 */
	protected function options()
	{
		return array(
			'instruction' => '',
			'notes'       => '',
			'size'        => 'full',
			'controls'    => 1,
			'mute'        => 0,
			'autoplay'    => 0,
			'loop'        => 0,
		);
	}

	public function get_field($index, $value)
	{
		$name     = $this->get_field_name_in_editor($index);
		$disabled = $this->get_disable_attribute($index);

		$btn_remove = sprintf(
			'<span class="btn-remove-file hide">%s</span>',
			esc_html__('Delete', 'smart-custom-fields')
		);

		$hide_class = 'hide';
		$media      = $btn_remove;
		$media_src  = null;
		$media_alt  = '';
		$isVideo  =  false;
		$attachment_id = false;

		// Инициализация параметров по умолчанию
		$field_data = array(
			'id' => '',
			'type' => '',
			'controls' => $this->get('controls'),
			'mute' => $this->get('mute'),
			'autoplay' => $this->get('autoplay'),
			'loop' => $this->get('loop')
		);

		// Обработка сохраненных данных
		if ($value && $value[0]) {
			// Если значение является JSON строкой
			if (is_string($value[0]) && is_array(json_decode($value[0], true))) {
				$data = json_decode($value[0], true);
				$field_data['id'] = isset($data['id']) ? $data['id'] : '';
				$field_data['type'] = isset($data['type']) ? $data['type'] : '';
				$field_data['controls'] = isset($data['controls']) ? $data['controls'] : $this->get('controls');
				$field_data['mute'] = isset($data['mute']) ? $data['mute'] : $this->get('mute');
				$field_data['autoplay'] = isset($data['autoplay']) ? $data['autoplay'] : $this->get('autoplay');
				$field_data['loop'] = isset($data['loop']) ? $data['loop'] : $this->get('loop');
			}


			$attachment_id = $field_data['id'];

			if ($attachment_id) {
				$media_alt = get_the_title($attachment_id);

				// Определяем тип вложения, если он не задан
				if (empty($field_data['type'])) {
					$mime_type = get_post_mime_type($attachment_id);
					$field_data['type'] = (strpos($mime_type, 'video') !== false) ? 'video' : 'image';
				}
				$isVideo = ($field_data['type'] === 'video');

				if ($isVideo) {
					$media_src = wp_get_attachment_url($attachment_id);
				} else {
					$image_src = wp_get_attachment_image_src($attachment_id, $this->get('size'));
					if (is_array($image_src) && isset($image_src[0])) {
						$media_src = $image_src[0];
					}
				}
			}

			if ($media_src) {
				if ($isVideo) {
					// Подготовка атрибутов для видео
					$attrs = array();
					if ($field_data['controls']) {
						$attrs[] = 'controls';
					}
					if ($field_data['mute']) {
						$attrs[] = 'muted';
					}
					if ($field_data['autoplay']) {
						$attrs[] = 'autoplay';
						$attrs[] = 'playsinline'; // Для мобильных устройств
					}
					if ($field_data['loop']) {
						$attrs[] = 'loop';
					}
					$attrs_str = implode(' ', $attrs);

					$media = sprintf(
						'<video src="%s" %s></video>%s',
						esc_url($media_src),
						$attrs_str,
						$btn_remove
					);
				} else {
					$media = sprintf(
						'<a href="%s" target="_blank"><img src="%s" alt="%s" /></a>%s',
						esc_url(wp_get_attachment_url($attachment_id) ?: $media_src),
						esc_url($media_src),
						esc_attr($media_alt),
						$btn_remove
					);
				}
				$hide_class = '';
			}
		}

		$field_id = 'scf-media-' . $index;


		$html = sprintf(
			'<div class="scf-media-wrapper" id="%s">
				          <span class="button btn-add-media" style="margin-bottom: 10px">%s</span><br />
				          <span class="%s %s">%s</span>
				          <div class="scf-video-controls" style="%s">
				          	  <input type="hidden" name="%s" value=\'%s\' %s  />
				              <label style="display: block; margin-bottom: 5px;">
				                  <input type="checkbox" class="scf-video-control" data-param="controls" %s>
				                  %s
				              </label>
				              <label style="display: block; margin-bottom: 5px;">
				                  <input type="checkbox" class="scf-video-control" data-param="mute" %s>
				                  %s
				              </label>
				              <label style="display: block; margin-bottom: 5px;">
				                  <input type="checkbox" class="scf-video-control" data-param="autoplay" %s>
				                  %s
				              </label>
				              <label style="display: block; margin-bottom: 5px;">
				                  <input type="checkbox" class="scf-video-control" data-param="loop" %s>
				                  %s
				              </label>
				          </div>
				      </div>',
			esc_attr($field_id),
			esc_html__('File Select', 'smart-custom-fields'),
			esc_attr(SCF_Config::PREFIX . 'upload-file'),
			esc_attr($hide_class),
			wp_kses_post($media),
			esc_attr($isVideo ? 'margin-top: 10px;display: flex;flex-wrap:wrap;' : 'display: none;'),
			esc_attr($name),
			esc_attr(json_encode($field_data)),
			disabled(true, $disabled, false),
			($field_data['controls'] ? 'checked' : ''),
			esc_html__('Показать элементы управления', 'smart-custom-fields'),
			($field_data['mute'] ? 'checked' : ''),
			esc_html__('Без звука', 'smart-custom-fields'),
			($field_data['autoplay'] ? 'checked' : ''),
			esc_html__('Автовоспроизведение (не работает со звуком)', 'smart-custom-fields'),
			($field_data['loop'] ? 'checked' : ''),
			esc_html__('Зациклить по кругу', 'smart-custom-fields')
		);
		// Добавляем JS для медиавыбора и превью
		$html .= "<script>
		jQuery(document).ready(function($) {
			let wrapper = $('#$field_id');
			let button = wrapper.find('.btn-add-media');
			let remove = wrapper.find('.btn-remove-file');
			let input  = wrapper.find('input[type=hidden]');
			let media  = wrapper.find('.smart-cf-upload-file');
			let controls  = wrapper.find('.scf-video-controls');
			let frame;

			// Получаем значения параметров из PHP
			let videoParams = {
				controls: " . ($field_data['controls'] ? 'true' : 'false') . ",
				mute: " . ($field_data['mute'] ? 'true' : 'false') . ",
				autoplay: " . ($field_data['autoplay'] ? 'true' : 'false') . ",
				loop: " . ($field_data['loop'] ? 'true' : 'false') . "
			};

			// Обработчик изменений чекбоксов
			controls.find('.scf-video-control').on('change', function() {
				let param = $(this).data('param');
				videoParams[param] = $(this).is(':checked');
				
				// Обновляем скрытое поле с параметрами
				let fieldData = JSON.parse(input.val());
				fieldData[param] = videoParams[param];
				input.val(JSON.stringify(fieldData));
				
				// Обновляем отображение видео, если оно есть
				let video = media.find('video');
				if (video.length > 0) {
					let src = video.attr('src');
					let attrs = [];
					if (videoParams.controls) attrs.push('controls');
					if (videoParams.mute) attrs.push('muted');
					if (videoParams.autoplay) {
						attrs.push('autoplay');
						attrs.push('playsinline');
					}
					if (videoParams.loop) attrs.push('loop');
					let attrs_str = attrs.join(' ');
					media.html('<video src=\"' + src + '\" ' + attrs_str + '></video><span class=\"btn-remove-file\">Удалить</span>');
				}
			});

			// Клик по кнопке выбора
			button.on('click', function(e) {
				e.preventDefault();

				// if (frame) frame.open();
				frame = wp.media({
					title: 'Выберите медиа',
					library: { type: 'image,video' },
					multiple: false,
					button: { text: 'Использовать' }
				});

				frame.on('select', function() {
					let attachment = frame.state().get('selection').first().toJSON();
					let src = attachment.url;
					let type = attachment.type;
					
					// Сохраняем ID и обновляем параметры
					input.val(attachment.id);
					let fieldData = {
						id: attachment.id,
						type: attachment.type,
						controls: videoParams.controls,
						mute: videoParams.mute,
						autoplay: videoParams.autoplay,
						loop: videoParams.loop
					};
					input.val(JSON.stringify(fieldData));
					
					if (type === 'image') {
						controls.css('display', 'none');
						media.html('<img src=\"' + src + '\" alt=\"\" /><span class=\"btn-remove-file\">Удалить</span>');
						controls.css('display', 'none');
					} else if (type === 'video') {
						// Формируем атрибуты видео
						let attrs = [];
						if (videoParams.controls) attrs.push('controls');
						if (videoParams.mute) attrs.push('muted');
						if (videoParams.autoplay) {
							attrs.push('autoplay');
							attrs.push('playsinline');
						}
						if (videoParams.loop) attrs.push('loop');
						let attrs_str = attrs.join(' ');
						controls.css('display', 'flex');
							
						media.html('<video src=\"' + src + '\" ' + attrs_str + '></video><span class=\"btn-remove-file\">Удалить</span>');
					} else {
						media.html('<a href=\"' + src + '\" target=\"_blank\">Просмотреть файл</a><span class=\"btn-remove-file\">Удалить</span>');
					}
					media.removeClass('hide');
					remove = media.find('.btn-remove-file');
					frame.close();					
				});
					
				frame.open();
			});

			$(document).on('click', '.btn-remove-file', bindRemove);

			// Удаление
			function bindRemove() {
				input.val('');
				controls.css('display', 'none');
				media.addClass('hide').html('<span class=\"btn-remove-file hide\">Удалить</span>');
			}
		});
		</script>
		";

		return $html;
	}

	/**
	 * Displaying the option fields in custom field settings page.
	 *
	 * @param int $group_key Group key.
	 * @param int $field_key Field key.
	 */
	public function display_field_options($group_key, $field_key)
	{
		$this->display_label_option($group_key, $field_key);
		$this->display_name_option($group_key, $field_key);
?>
		<tr>
			<th><?php esc_html_e('Instruction', 'smart-custom-fields'); ?></th>
			<td>
				<textarea name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'instruction')); ?>"
					class="widefat" rows="5"><?php echo esc_attr($this->get('instruction')); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Notes', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'notes')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('notes')); ?>" />
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Video Controls', 'smart-custom-fields'); ?></th>
			<td>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'controls')); ?>"
						value="1" <?php checked($this->get('controls'), 1); ?> />
					<?php esc_html_e('Show video controls', 'smart-custom-fields'); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Mute Video', 'smart-custom-fields'); ?></th>
			<td>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'mute')); ?>"
						value="1" <?php checked($this->get('mute'), 1); ?> />
					<?php esc_html_e('Mute video by default', 'smart-custom-fields'); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Autoplay Video', 'smart-custom-fields'); ?></th>
			<td>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'autoplay')); ?>"
						value="1" <?php checked($this->get('autoplay'), 1); ?> />
					<?php esc_html_e('Autoplay video', 'smart-custom-fields'); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Loop Video', 'smart-custom-fields'); ?></th>
			<td>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'loop')); ?>"
						value="1" <?php checked($this->get('loop'), 1); ?> />
					<?php esc_html_e('Loop video', 'smart-custom-fields'); ?>
				</label>
			</td>
		</tr>
<?php
	}
}

function getSCFMedia($data)
{
	if (empty($data) || empty($data[0])) return;

	$field_data = json_decode($data[0], true);

	$id = $field_data['id'];
	$type = $field_data['type'];
	$isVideo = ($type === 'video');

	if (empty($id)) return;

	$url = wp_get_attachment_url($id);

	if ($isVideo) {
		// Подготовка атрибутов для видео
		$attrs = array();
		if ($field_data['controls']) {
			$attrs[] = 'controls';
		}
		if ($field_data['mute']) {
			$attrs[] = 'muted';
		}
		if ($field_data['autoplay']) {
			$attrs[] = 'autoplay';
			$attrs[] = 'playsinline'; // Для мобильных устройств
		}
		if ($field_data['loop']) {
			$attrs[] = 'loop';
		}
		$attrs_str = implode(' ', $attrs);

		echo '<video src="' . $url . '" ' . $attrs_str . '></video>';
	} else {
		echo '<img src="' . $url . '" alt="">';
	}
}

function scf_add_media_field()
{
	new Smart_Custom_Fields_Field_Media();
}

add_action('init', 'scf_add_media_field');
