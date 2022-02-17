<?php
namespace Photonic_Plugin\Admin\Forms;

/**
 * Creates a form in the "Add Media" screen under the new "Photonic" tab. This form lets you insert the gallery shortcode with
 * the right arguments for native WP galleries, Flickr, Google Photos, SmugMug, Zenfolio and Instagram.
 *
 */

class Add_Gallery {
	private static $instance = null;

	private function __construct() {
	}

	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new Add_Gallery();
		}
		return self::$instance;
	}

	function build_form() {
		global $photonic_alternative_shortcode;
		$shortcode = empty($photonic_alternative_shortcode) ? 'gallery' : $photonic_alternative_shortcode;

		$selected_tab = isset($_GET['photonic-tab']) ? esc_attr($_GET['photonic-tab']) : 'default';
		if (!in_array($selected_tab, ['default', 'flickr', 'google', 'smugmug', 'zenfolio', 'instagram'])) {
			$selected_tab = 'default';
		}

		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				window.photonicAdminHtmlEncode = function photonicAdminHtmlEncode(value){
					return $('<div/>').text(value).html();
				};

				$('#photonic-shortcode-form input[type="text"], #photonic-shortcode-form select').change(function() {
					var comboValues = $('#photonic-shortcode-form').serializeArray();
					var newValues = [];
					var len = comboValues.length;

					$(comboValues).each(function(i, obj) {
						var individual = this;
						if (individual['name'].trim() !== 'photonic-shortcode' && individual['name'].trim() !== 'photonic-submit' &&
							individual['name'].trim() !== 'photonic-cancel' && individual['value'].trim() !== '') {
							newValues.push(individual['name'] + "='" + photonicAdminHtmlEncode(decodeURIComponent(individual['value'].trim())) + "'");
						}
					});

					var shortcode = "[<?php echo $shortcode; ?> type='<?php echo $selected_tab; ?>' ";
					len = newValues.length;
					$(newValues).each(function() {
						shortcode += this + ' ';
					});
					shortcode += ']';

					$('#photonic-preview').text(shortcode);
					$('#photonic-shortcode').val(shortcode);
				});
				$('#photonic-shortcode-form select').change();
			});
		</script>
		<?php
		require_once(PHOTONIC_PATH.'/Admin/Forms/Vanilla_Form.php');
		$form = Vanilla_Form::get_instance();
		$fields = $form->get_fields();

		echo "<form id='photonic-shortcode-form' method='post' action=''>";
		$this->build_tabs($selected_tab, $fields);
	}

	function  build_tabs($selected_tab, $fields) {
		$tab_list = '';
		$field_list = [];
		$prelude = '';
		foreach ($fields as $tab => $field_group) {
			$tab_list .= "<li><a href='".esc_url(add_query_arg(['photonic-tab' => $tab]))."' class='".($tab == $selected_tab ? 'current' : '')."'>".esc_attr($field_group['name'])."</a> | </li>";
			if ($tab == $selected_tab) {
				$field_list = $field_group['fields'];
				$prelude = isset($field_group['prelude']) ? $field_group['prelude'] : '';
			}
		}

		echo "<ul class='subsubsub'>";
		if (strlen($tab_list) > 8) {
			$tab_list = substr($tab_list, 0, -8);
		}
		echo $tab_list;
		echo "</ul>";

		if (!empty($prelude)) {
			echo "<p class='prelude'>"; print_r($prelude); echo "</p>";
		}

		$this->build_table($field_list);
		$this->show_shortcode_preview();
	}

	function build_table($field_list) {
		echo "<table class='photonic-form'>";
		foreach ($field_list as $field) {
			echo "<tr>";
			echo "<th scope='row'>{$field['name']} ".(isset($field['req']) && $field['req'] ? '(*)' : '')." </th>";
			$alt_id = !empty($field['alt_id']) ? " alt_id='{$field['alt_id']}' " : '';
			switch ($field['type']) {
				case 'text':
					echo "<td><input type='text' name='{$field['id']}' value='".(isset($field['std']) ? $field['std'] : '')."' $alt_id/></td>";
					break;

				case 'select':
					echo "<td><select name='{$field['id']}' $alt_id>";
					$default = isset($field['std']) ? $field['std'] : '';
					foreach ($field['options'] as $option_name => $option_value) {
						if ($option_name == $default) {
							$selected = 'selected';
						}
						else {
							$selected = '';
						}
						echo "<option value='$option_name' $selected>".esc_attr($option_value)."</option>";
					}
					echo "</select></td>";
					break;

				case 'raw':
					echo "<td>".$field['std']."</td>";
					break;
			}
			echo "<td class='hint'>".(isset($field['hint']) ? $field['hint'] : '')."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	function show_shortcode_preview() {
		echo "<div class='preview'>";
		echo "<script type='text/javascript'></script>";
		echo "<h4>".esc_html__('Shortcode preview', 'photonic')."</h4>";
		echo "<pre class='html' id='photonic-preview' name='photonic-preview'></pre>";
		echo "<input type='hidden' id='photonic-shortcode' name='photonic-shortcode' />";
		echo "</div>";

		echo "<div class='button-panel'>";
		echo get_submit_button(esc_html__('Insert into post', 'photonic'), 'primary', 'photonic-submit', false);
		echo get_submit_button(esc_html__('Cancel', 'photonic'), 'delete', 'photonic-cancel', false);
		echo "</div>";
	}
}

if (isset($_POST['photonic-submit'])) {
	$shortcode =  stripslashes($_POST['photonic-shortcode']);
	media_send_to_editor($shortcode);
	return;
}
else if (isset($_POST['photonic-cancel'])) {
	media_send_to_editor('');
	return;
}

$add_gallery = Add_Gallery::get_instance();
$add_gallery->build_form();
