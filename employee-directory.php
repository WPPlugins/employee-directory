<?php
/** 
 * Plugin Name: Employee Directory
 * Plugin URI: https://emarketdesign.com
 * Description: Employee directory WordPress plugin provides an easy to use and maintain solution for any organization needing a centralized company directory.
 * Version: 3.6.2
 * Author: emarket-design
 * Author URI: https://emarketdesign.com
 * Text Domain: empd-com
 * Domain Path: /lang
 * @package EMPD_COM
 * @since WPAS 4.0
 */
/*
 * LICENSE:
 * Employee Directory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Employee Directory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * Please see <http://www.gnu.org/licenses/> for details.
*/
if (!defined('ABSPATH')) exit;
if (!class_exists('Employee_Directory')):
	/**
	 * Main class for Employee Directory
	 *
	 * @class Employee_Directory
	 */
	final class Employee_Directory {
		/**
		 * @var Employee_Directory single instance of the class
		 */
		private static $_instance;
		public $textdomain = 'empd-com';
		public $app_name = 'empd_com';
		public $session;
		/**
		 * Main Employee_Directory Instance
		 *
		 * Ensures only one instance of Employee_Directory is loaded or can be loaded.
		 *
		 * @static
		 * @see EMPD_COM()
		 * @return Employee_Directory - Main instance
		 */
		public static function instance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new self();
				self::$_instance->define_constants();
				self::$_instance->includes();
				self::$_instance->load_plugin_textdomain();
				self::$_instance->session = new Emd_Session('empd_com');
				add_filter('the_content', array(
					self::$_instance,
					'change_content'
				));
				add_action('admin_menu', array(
					self::$_instance,
					'display_settings'
				));
				add_filter('template_include', array(
					self::$_instance,
					'show_template'
				));
				add_action('widgets_init', array(
					self::$_instance,
					'include_widgets'
				));
			}
			return self::$_instance;
		}
		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', $this->textdomain) , '1.0');
		}
		/**
		 * Define Employee_Directory Constants
		 *
		 * @access private
		 * @return void
		 */
		private function define_constants() {
			define('EMPD_COM_VERSION', '3.6.2');
			define('EMPD_COM_AUTHOR', 'emarket-design');
			define('EMPD_COM_NAME', 'Employee Directory');
			define('EMPD_COM_PLUGIN_FILE', __FILE__);
			define('EMPD_COM_PLUGIN_DIR', plugin_dir_path(__FILE__));
			define('EMPD_COM_PLUGIN_URL', plugin_dir_url(__FILE__));
		}
		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {
			//these files are in all apps
			if (!function_exists('emd_mb_meta')) {
				require_once EMPD_COM_PLUGIN_DIR . 'assets/ext/emd-meta-box/emd-meta-box.php';
			}
			if (!function_exists('emd_translate_date_format')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/date-functions.php';
			}
			if (!function_exists('emd_get_hidden_func')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/common-functions.php';
			}
			if (!class_exists('Emd_Entity')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/entities/class-emd-entity.php';
			}
			if (!function_exists('emd_get_template_part')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/layout-functions.php';
			}
			if (!class_exists('EDD_SL_Plugin_Updater')) {
				require_once EMPD_COM_PLUGIN_DIR . 'assets/ext/edd/EDD_SL_Plugin_Updater.php';
			}
			if (!class_exists('Emd_License')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/class-emd-license.php';
			}
			if (!function_exists('emd_show_license_page')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/license-functions.php';
			}
			//the rest
			if (!class_exists('Emd_Query')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/class-emd-query.php';
			}
			if (!class_exists('Zebra_Form')) {
				require_once EMPD_COM_PLUGIN_DIR . '/assets/ext/zebraform/Zebra_Form.php';
			}
			if (!function_exists('emd_submit_form')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/form-functions.php';
			}
			if (!function_exists('emd_shc_get_layout_list')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/shortcode-functions.php';
			}
			if (!class_exists('Emd_Widget')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/class-emd-widget.php';
			}
			if (!class_exists('Emd_Session')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/class-emd-session.php';
			}
			if (!function_exists('emd_show_login_register_forms')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/login-register-functions.php';
			}
			do_action('emd_ext_include_files');
			//app specific files
			if (!function_exists('emd_show_settings_page')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/settings-functions.php';
			}
			if (!function_exists('emd_form_register_settings')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/settings-functions-forms.php';
			}
			if (!function_exists('emd_global_register_settings')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/settings-functions-globs.php';
			}
			if (!function_exists('emd_misc_register_settings')) {
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/settings-functions-misc.php';
			}
			if (is_admin()) {
				//these files are in all apps
				if (!function_exists('emd_display_store')) {
					require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/store-functions.php';
				}
				//the rest
				if (!function_exists('emd_shc_button')) {
					require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/wpas-btn-functions.php';
				}
				if (!class_exists('Emd_Single_Taxonomy')) {
					require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/singletax/class-emd-single-taxonomy.php';
					require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/singletax/class-emd-walker-radio.php';
				}
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/glossary.php';
				require_once EMPD_COM_PLUGIN_DIR . 'includes/admin/getting-started.php';
			}
			require_once EMPD_COM_PLUGIN_DIR . 'includes/plugin-app-functions.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/class-install-deactivate.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/entities/class-emd-employee.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/entities/emd-employee-shortcodes.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/forms.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/scripts.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/plugin-feedback-functions.php';
			require_once EMPD_COM_PLUGIN_DIR . 'includes/content-functions.php';
		}
		/**
		 * Loads plugin language files
		 *
		 * @access public
		 * @return void
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters('plugin_locale', get_locale() , 'empd-com');
			$mofile = sprintf('%1$s-%2$s.mo', 'empd-com', $locale);
			$mofile_shared = sprintf('%1$s-emd-plugins-%2$s.mo', 'empd-com', $locale);
			$lang_file_list = Array(
				'emd-plugins' => $mofile_shared,
				'empd-com' => $mofile
			);
			foreach ($lang_file_list as $lang_key => $lang_file) {
				$localmo = EMPD_COM_PLUGIN_DIR . '/lang/' . $lang_file;
				$globalmo = WP_LANG_DIR . '/empd-com/' . $lang_file;
				if (file_exists($globalmo)) {
					load_textdomain($lang_key, $globalmo);
				} elseif (file_exists($localmo)) {
					load_textdomain($lang_key, $localmo);
				} else {
					load_plugin_textdomain($lang_key, false, EMPD_COM_PLUGIN_DIR . '/lang/');
				}
			}
		}
		/**
		 * Changes content on frontend views
		 *
		 * @access public
		 * @param string $content
		 *
		 * @return string $content
		 */
		public function change_content($content) {
			$tools = get_option($this->app_name . '_tools');
			if (!is_admin() && !empty($tools['disable_emd_templates'])) {
				if (post_password_required()) {
					$content = get_the_password_form();
				} else {
					$mypost_type = get_post_type();
					if ($mypost_type == 'post' || $mypost_type == 'page') {
						$mypost_type = "emd_" . $mypost_type;
					}
					$ent_list = get_option($this->app_name . '_ent_list');
					if (in_array($mypost_type, array_keys($ent_list)) && class_exists($mypost_type)) {
						$func = "change_content";
						$obj = new $mypost_type;
						$content = $obj->$func($content);
					}
				}
			}
			return $content;
		}
		/**
		 * Creates plugin page in menu with submenus
		 *
		 * @access public
		 * @return void
		 */
		public function display_settings() {
			add_menu_page(__('EDirectory', $this->textdomain) , __('EDirectory', $this->textdomain) , 'manage_options', $this->app_name, array(
				$this,
				'display_getting_started_page'
			));
			add_submenu_page($this->app_name, __('Getting Started', $this->textdomain) , __('Getting Started', $this->textdomain) , 'manage_options', $this->app_name);
			add_submenu_page($this->app_name, __('Glossary', $this->textdomain) , __('Glossary', $this->textdomain) , 'manage_options', $this->app_name . '_glossary', array(
				$this,
				'display_glossary_page'
			));
			add_submenu_page($this->app_name, __('Settings', $this->textdomain) , __('Settings', $this->textdomain) , 'manage_options', $this->app_name . '_settings', array(
				$this,
				'display_settings_page'
			));
			add_submenu_page($this->app_name, __('Add-Ons', $this->textdomain) , __('Add-Ons', $this->textdomain) , 'manage_options', $this->app_name . '_store', array(
				$this,
				'display_store_page'
			));
			add_submenu_page($this->app_name, __('Designs', $this->textdomain) , __('Designs', $this->textdomain) , 'manage_options', $this->app_name . '_designs', array(
				$this,
				'display_design_page'
			));
			add_submenu_page($this->app_name, __('Support', $this->textdomain) , __('Support', $this->textdomain) , 'manage_options', $this->app_name . '_support', array(
				$this,
				'display_support_page'
			));
			$emd_lic_settings = get_option('emd_license_settings', Array());
			$show_lic_page = 0;
			if (!empty($emd_lic_settings)) {
				foreach ($emd_lic_settings as $key => $val) {
					if ($key == $this->app_name) {
						$show_lic_page = 1;
						break;
					} else if ($val['type'] == 'ext') {
						$show_lic_page = 1;
						break;
					}
				}
				if ($show_lic_page == 1) {
					add_submenu_page($this->app_name, __('Licenses', $this->textdomain) , __('Licenses', $this->textdomain) , 'manage_options', $this->app_name . '_licenses', array(
						$this,
						'display_licenses_page'
					));
				}
			}
			//add submenu page under app settings page
			do_action('emd_ext_add_menu_pages', $this->app_name);
		}
		/**
		 * Calls settings function to display glossary page
		 *
		 * @access public
		 * @return void
		 */
		public function display_glossary_page() {
			do_action($this->app_name . '_settings_glossary');
		}
		public function display_getting_started_page() {
			do_action($this->app_name . '_getting_started');
		}
		public function display_store_page() {
			emd_display_store($this->textdomain);
		}
		public function display_design_page() {
			emd_display_design($this->textdomain);
		}
		public function display_support_page() {
			emd_display_support($this->textdomain, 2, 'employee-directory');
		}
		public function display_licenses_page() {
			do_action('emd_show_license_page', $this->app_name);
		}
		public function display_settings_page() {
			do_action('emd_show_settings_page', $this->app_name);
		}
		/**
		 * Displays single, archive, tax and no-access frontend views
		 *
		 * @access public
		 * @return string, $template:emd template or template
		 */
		public function show_template($template) {
			return emd_show_template($this->app_name, EMPD_COM_PLUGIN_DIR, $template);
		}
		/**
		 * Loads sidebar widgets
		 *
		 * @access public
		 * @return void
		 */
		public function include_widgets() {
			require_once EMPD_COM_PLUGIN_DIR . 'includes/entities/class-emd-employee-widgets.php';
		}
	}
endif;
/**
 * Returns the main instance of Employee_Directory
 *
 * @return Employee_Directory
 */
function EMPD_COM() {
	return Employee_Directory::instance();
}
// Get the Employee_Directory instance
EMPD_COM();