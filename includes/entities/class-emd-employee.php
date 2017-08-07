<?php
/**
 * Entity Class
 *
 * @package EMPD_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Employee Class
 * @since WPAS 4.0
 */
class Emd_Employee extends Emd_Entity {
	protected $post_type = 'emd_employee';
	protected $textdomain = 'empd-com';
	protected $sing_label;
	protected $plural_label;
	protected $menu_entity;
	protected $id;
	/**
	 * Initialize entity class
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function __construct() {
		add_action('init', array(
			$this,
			'set_filters'
		) , 1);
		add_action('admin_init', array(
			$this,
			'set_metabox'
		));
		add_filter('post_updated_messages', array(
			$this,
			'updated_messages'
		));
		$is_adv_filt_ext = apply_filters('emd_adv_filter_on', 0);
		if ($is_adv_filt_ext === 0) {
			add_action('manage_emd_employee_posts_custom_column', array(
				$this,
				'custom_columns'
			) , 10, 2);
			add_filter('manage_emd_employee_posts_columns', array(
				$this,
				'column_headers'
			));
		}
		add_filter('is_protected_meta', array(
			$this,
			'hide_attrs'
		) , 10, 2);
		add_filter('postmeta_form_keys', array(
			$this,
			'cust_keys'
		) , 10, 2);
		add_filter('emd_get_cust_fields', array(
			$this,
			'get_cust_fields'
		) , 10, 2);
		add_filter('enter_title_here', array(
			$this,
			'change_title_text'
		));
	}
	public function change_title_disable_emd_temp($title, $id) {
		$post = get_post($id);
		if ($this->post_type == $post->post_type && (!empty($this->id) && $this->id == $id)) {
			return '';
		}
		return $title;
	}
	/**
	 * Get custom attribute list
	 * @since WPAS 4.9
	 *
	 * @param array $cust_fields
	 * @param string $post_type
	 *
	 * @return array $new_keys
	 */
	public function get_cust_fields($cust_fields, $post_type) {
		global $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
               FROM $wpdb->postmeta a
               WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
			if (!empty($keys)) {
				foreach ($keys as $i => $mkey) {
					if (!preg_match('/^(_|wpas_|emd_)/', $mkey)) {
						$ckey = str_replace('-', '_', sanitize_title($mkey));
						$cust_fields[$ckey] = $mkey;
					}
				}
			}
		}
		return $cust_fields;
	}
	/**
	 * Set new custom attributes dropdown in admin edit entity
	 * @since WPAS 4.9
	 *
	 * @param array $keys
	 * @param object $post
	 *
	 * @return array $keys
	 */
	public function cust_keys($keys, $post) {
		global $post_type, $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
                FROM $wpdb->postmeta a
                WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
		}
		return $keys;
	}
	/**
	 * Hide all emd attributes
	 * @since WPAS 4.9
	 *
	 * @param bool $protected
	 * @param string $meta_key
	 *
	 * @return bool $protected
	 */
	public function hide_attrs($protected, $meta_key) {
		if (preg_match('/^(emd_|wpas_)/', $meta_key)) return true;
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($meta_key == $fkey) return true;
			}
		}
		return $protected;
	}
	/**
	 * Get column header list in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function column_headers($columns) {
		$ent_list = get_option(str_replace("-", "_", $this->textdomain) . '_ent_list');
		if (!empty($ent_list[$this->post_type]['featured_img'])) {
			$columns['featured_img'] = __('Featured Image', $this->textdomain);
		}
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if (!in_array($fkey, Array(
					'wpas_form_name',
					'wpas_form_submitted_by',
					'wpas_form_submitted_ip'
				)) && !in_array($mybox_field['type'], Array(
					'textarea',
					'wysiwyg'
				)) && $mybox_field['list_visible'] == 1) {
					$columns[$fkey] = $mybox_field['name'];
				}
			}
		}
		$taxonomies = get_object_taxonomies($this->post_type, 'objects');
		if (!empty($taxonomies)) {
			$tax_list = get_option(str_replace("-", "_", $this->textdomain) . '_tax_list');
			foreach ($taxonomies as $taxonomy) {
				if (!empty($tax_list[$this->post_type][$taxonomy->name]) && $tax_list[$this->post_type][$taxonomy->name]['list_visible'] == 1) {
					$columns[$taxonomy->name] = $taxonomy->label;
				}
			}
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list)) {
			foreach ($rel_list as $krel => $rel) {
				if ($rel['from'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'from'
				))) {
					$columns[$krel] = $rel['from_title'];
				} elseif ($rel['to'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'to'
				))) {
					$columns[$krel] = $rel['to_title'];
				}
			}
		}
		return $columns;
	}
	/**
	 * Get custom column values in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param int $column_id
	 * @param int $post_id
	 *
	 * @return string $value
	 */
	public function custom_columns($column_id, $post_id) {
		if (taxonomy_exists($column_id) == true) {
			$terms = get_the_terms($post_id, $column_id);
			$ret = array();
			if (!empty($terms)) {
				foreach ($terms as $term) {
					$url = add_query_arg(array(
						'post_type' => $this->post_type,
						'term' => $term->slug,
						'taxonomy' => $column_id
					) , admin_url('edit.php'));
					$a_class = preg_replace('/^emd_/', '', $this->post_type);
					$ret[] = sprintf('<a href="%s"  class="' . $a_class . '-tax ' . $term->slug . '">%s</a>', $url, $term->name);
				}
			}
			echo implode(', ', $ret);
			return;
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list) && !empty($rel_list[$column_id])) {
			$rel_arr = $rel_list[$column_id];
			if ($rel_arr['from'] == $this->post_type) {
				$other_ptype = $rel_arr['to'];
			} elseif ($rel_arr['to'] == $this->post_type) {
				$other_ptype = $rel_arr['from'];
			}
			$column_id = str_replace('rel_', '', $column_id);
			if (function_exists('p2p_type') && p2p_type($column_id)) {
				$rel_args = apply_filters('emd_ext_p2p_add_query_vars', array(
					'posts_per_page' => - 1
				) , Array(
					$other_ptype
				));
				$connected = p2p_type($column_id)->get_connected($post_id, $rel_args);
				$ptype_obj = get_post_type_object($this->post_type);
				$edit_cap = $ptype_obj->cap->edit_posts;
				$ret = array();
				if (empty($connected->posts)) return '&ndash;';
				foreach ($connected->posts as $myrelpost) {
					$rel_title = get_the_title($myrelpost->ID);
					$rel_title = apply_filters('emd_ext_p2p_connect_title', $rel_title, $myrelpost, '');
					$url = get_permalink($myrelpost->ID);
					$url = apply_filters('emd_ext_connected_ptype_url', $url, $myrelpost, $edit_cap);
					$ret[] = sprintf('<a href="%s" title="%s" target="_blank">%s</a>', $url, $rel_title, $rel_title);
				}
				echo implode(', ', $ret);
				return;
			}
		}
		$value = get_post_meta($post_id, $column_id, true);
		$type = "";
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($fkey == $column_id) {
					$type = $mybox_field['type'];
					break;
				}
			}
		}
		if ($column_id == 'featured_img') {
			$type = 'featured_img';
		}
		switch ($type) {
			case 'featured_img':
				$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id) , 'thumbnail');
				if (!empty($thumb_url)) {
					$value = "<img style='max-width:100%;height:auto;' src='" . $thumb_url[0] . "' >";
				}
			break;
			case 'plupload_image':
			case 'image':
			case 'thickbox_image':
				$image_list = emd_mb_meta($column_id, 'type=image');
				$value = "";
				if (!empty($image_list)) {
					$myimage = current($image_list);
					$value = "<img style='max-width:100%;height:auto;' src='" . $myimage['url'] . "' >";
				}
			break;
			case 'user':
			case 'user-adv':
				$user_id = emd_mb_meta($column_id);
				if (!empty($user_id)) {
					$user_info = get_userdata($user_id);
					$value = $user_info->display_name;
				}
			break;
			case 'file':
				$file_list = emd_mb_meta($column_id, 'type=file');
				if (!empty($file_list)) {
					$value = "";
					foreach ($file_list as $myfile) {
						$fsrc = wp_mime_type_icon($myfile['ID']);
						$value.= "<a href='" . $myfile['url'] . "' target='_blank'><img src='" . $fsrc . "' title='" . $myfile['name'] . "' width='20' /></a>";
					}
				}
			break;
			case 'radio':
			case 'checkbox_list':
			case 'select':
			case 'select_advanced':
				$value = emd_get_attr_val(str_replace("-", "_", $this->textdomain) , $post_id, $this->post_type, $column_id);
			break;
			case 'checkbox':
				if ($value == 1) {
					$value = '<span class="dashicons dashicons-yes"></span>';
				} elseif ($value == 0) {
					$value = '<span class="dashicons dashicons-no-alt"></span>';
				}
			break;
			case 'rating':
				$value = apply_filters('emd_get_rating_value', $value, Array(
					'meta' => $column_id
				) , $post_id);
			break;
		}
		if (is_array($value)) {
			$value = "<div class='clonelink'>" . implode("</div><div class='clonelink'>", $value) . "</div>";
		}
		echo $value;
	}
	/**
	 * Register post type and taxonomies and set initial values for taxs
	 *
	 * @since WPAS 4.0
	 *
	 */
	public static function register() {
		$labels = array(
			'name' => __('Employees', 'empd-com') ,
			'singular_name' => __('Employee', 'empd-com') ,
			'add_new' => __('Add New', 'empd-com') ,
			'add_new_item' => __('Add New Employee', 'empd-com') ,
			'edit_item' => __('Edit Employee', 'empd-com') ,
			'new_item' => __('New Employee', 'empd-com') ,
			'all_items' => __('All Employees', 'empd-com') ,
			'view_item' => __('View Employee', 'empd-com') ,
			'search_items' => __('Search Employees', 'empd-com') ,
			'not_found' => __('No Employees Found', 'empd-com') ,
			'not_found_in_trash' => __('No Employees Found In Trash', 'empd-com') ,
			'menu_name' => __('Employees', 'empd-com') ,
		);
		$ent_map_list = get_option('empd_com_ent_map_list', Array());
		if (!empty($ent_map_list['emd_employee']['rewrite'])) {
			$rewrite = $ent_map_list['emd_employee']['rewrite'];
		} else {
			$rewrite = 'employees';
		}
		$supports = Array(
			'custom-fields',
		);
		if (empty($ent_map_list['emd_employee']['attrs']['blt_title']) || $ent_map_list['emd_employee']['attrs']['blt_title'] != 'hide') {
			$supports[] = 'title';
		}
		if (empty($ent_map_list['emd_employee']['attrs']['blt_content']) || $ent_map_list['emd_employee']['attrs']['blt_content'] != 'hide') {
			$supports[] = 'editor';
		}
		register_post_type('emd_employee', array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'description' => __('Employees are staff members working for your organization.', 'empd-com') ,
			'show_in_menu' => true,
			'menu_position' => 6,
			'has_archive' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'slug' => $rewrite
			) ,
			'can_export' => true,
			'show_in_rest' => false,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-businessman',
			'map_meta_cap' => 'true',
			'taxonomies' => array() ,
			'capability_type' => 'emd_employee',
			'supports' => $supports,
		));
		$employment_type_nohr_labels = array(
			'name' => __('Employment Types', 'empd-com') ,
			'singular_name' => __('Employment Type', 'empd-com') ,
			'search_items' => __('Search Employment Types', 'empd-com') ,
			'popular_items' => __('Popular Employment Types', 'empd-com') ,
			'all_items' => __('All', 'empd-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Employment Type', 'empd-com') ,
			'update_item' => __('Update Employment Type', 'empd-com') ,
			'add_new_item' => __('Add New Employment Type', 'empd-com') ,
			'new_item_name' => __('Add New Employment Type Name', 'empd-com') ,
			'separate_items_with_commas' => __('Seperate Employment Types with commas', 'empd-com') ,
			'add_or_remove_items' => __('Add or Remove Employment Types', 'empd-com') ,
			'choose_from_most_used' => __('Choose from the most used Employment Types', 'empd-com') ,
			'menu_name' => __('Employment Types', 'empd-com') ,
		);
		$tax_settings = get_option('empd_com_tax_settings', Array());
		if (empty($tax_settings['employment_type']['hide']) || (!empty($tax_settings['employment_type']['hide']) && $tax_settings['employment_type']['hide'] != 'hide')) {
			if (!empty($tax_settings['employment_type']['rewrite'])) {
				$rewrite = $tax_settings['employment_type']['rewrite'];
			} else {
				$rewrite = 'employment_type';
			}
			register_taxonomy('employment_type', array(
				'emd_employee'
			) , array(
				'hierarchical' => false,
				'labels' => $employment_type_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_employment_type',
					'edit_terms' => 'edit_employment_type',
					'delete_terms' => 'delete_employment_type',
					'assign_terms' => 'assign_employment_type'
				) ,
			));
		}
		$jobtitles_nohr_labels = array(
			'name' => __('Job Titles', 'empd-com') ,
			'singular_name' => __('Job Title', 'empd-com') ,
			'search_items' => __('Search Job Titles', 'empd-com') ,
			'popular_items' => __('Popular Job Titles', 'empd-com') ,
			'all_items' => __('All', 'empd-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Job Title', 'empd-com') ,
			'update_item' => __('Update Job Title', 'empd-com') ,
			'add_new_item' => __('Add New Job Title', 'empd-com') ,
			'new_item_name' => __('Add New Job Title Name', 'empd-com') ,
			'separate_items_with_commas' => __('Seperate Job Titles with commas', 'empd-com') ,
			'add_or_remove_items' => __('Add or Remove Job Titles', 'empd-com') ,
			'choose_from_most_used' => __('Choose from the most used Job Titles', 'empd-com') ,
			'menu_name' => __('Job Titles', 'empd-com') ,
		);
		$tax_settings = get_option('empd_com_tax_settings', Array());
		if (empty($tax_settings['jobtitles']['hide']) || (!empty($tax_settings['jobtitles']['hide']) && $tax_settings['jobtitles']['hide'] != 'hide')) {
			if (!empty($tax_settings['jobtitles']['rewrite'])) {
				$rewrite = $tax_settings['jobtitles']['rewrite'];
			} else {
				$rewrite = 'jobtitles';
			}
			register_taxonomy('jobtitles', array(
				'emd_employee'
			) , array(
				'hierarchical' => false,
				'labels' => $jobtitles_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_jobtitles',
					'edit_terms' => 'edit_jobtitles',
					'delete_terms' => 'delete_jobtitles',
					'assign_terms' => 'assign_jobtitles'
				) ,
			));
		}
		$gender_nohr_labels = array(
			'name' => __('Genders', 'empd-com') ,
			'singular_name' => __('Gender', 'empd-com') ,
			'search_items' => __('Search Genders', 'empd-com') ,
			'popular_items' => __('Popular Genders', 'empd-com') ,
			'all_items' => __('All', 'empd-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Gender', 'empd-com') ,
			'update_item' => __('Update Gender', 'empd-com') ,
			'add_new_item' => __('Add New Gender', 'empd-com') ,
			'new_item_name' => __('Add New Gender Name', 'empd-com') ,
			'separate_items_with_commas' => __('Seperate Genders with commas', 'empd-com') ,
			'add_or_remove_items' => __('Add or Remove Genders', 'empd-com') ,
			'choose_from_most_used' => __('Choose from the most used Genders', 'empd-com') ,
			'menu_name' => __('Genders', 'empd-com') ,
		);
		$tax_settings = get_option('empd_com_tax_settings', Array());
		if (empty($tax_settings['gender']['hide']) || (!empty($tax_settings['gender']['hide']) && $tax_settings['gender']['hide'] != 'hide')) {
			if (!empty($tax_settings['gender']['rewrite'])) {
				$rewrite = $tax_settings['gender']['rewrite'];
			} else {
				$rewrite = 'gender';
			}
			register_taxonomy('gender', array(
				'emd_employee'
			) , array(
				'hierarchical' => false,
				'labels' => $gender_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_gender',
					'edit_terms' => 'edit_gender',
					'delete_terms' => 'delete_gender',
					'assign_terms' => 'assign_gender'
				) ,
			));
		}
		$departments_nohr_labels = array(
			'name' => __('Departments', 'empd-com') ,
			'singular_name' => __('Department', 'empd-com') ,
			'search_items' => __('Search Departments', 'empd-com') ,
			'popular_items' => __('Popular Departments', 'empd-com') ,
			'all_items' => __('All', 'empd-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Department', 'empd-com') ,
			'update_item' => __('Update Department', 'empd-com') ,
			'add_new_item' => __('Add New Department', 'empd-com') ,
			'new_item_name' => __('Add New Department Name', 'empd-com') ,
			'separate_items_with_commas' => __('Seperate Departments with commas', 'empd-com') ,
			'add_or_remove_items' => __('Add or Remove Departments', 'empd-com') ,
			'choose_from_most_used' => __('Choose from the most used Departments', 'empd-com') ,
			'menu_name' => __('Departments', 'empd-com') ,
		);
		$tax_settings = get_option('empd_com_tax_settings', Array());
		if (empty($tax_settings['departments']['hide']) || (!empty($tax_settings['departments']['hide']) && $tax_settings['departments']['hide'] != 'hide')) {
			if (!empty($tax_settings['departments']['rewrite'])) {
				$rewrite = $tax_settings['departments']['rewrite'];
			} else {
				$rewrite = 'departments';
			}
			register_taxonomy('departments', array(
				'emd_employee'
			) , array(
				'hierarchical' => false,
				'labels' => $departments_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_departments',
					'edit_terms' => 'edit_departments',
					'delete_terms' => 'delete_departments',
					'assign_terms' => 'assign_departments'
				) ,
			));
		}
		$marital_status_nohr_labels = array(
			'name' => __('Marital Statuses', 'empd-com') ,
			'singular_name' => __('Marital Status', 'empd-com') ,
			'search_items' => __('Search Marital Statuses', 'empd-com') ,
			'popular_items' => __('Popular Marital Statuses', 'empd-com') ,
			'all_items' => __('All', 'empd-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Marital Status', 'empd-com') ,
			'update_item' => __('Update Marital Status', 'empd-com') ,
			'add_new_item' => __('Add New Marital Status', 'empd-com') ,
			'new_item_name' => __('Add New Marital Status Name', 'empd-com') ,
			'separate_items_with_commas' => __('Seperate Marital Statuses with commas', 'empd-com') ,
			'add_or_remove_items' => __('Add or Remove Marital Statuses', 'empd-com') ,
			'choose_from_most_used' => __('Choose from the most used Marital Statuses', 'empd-com') ,
			'menu_name' => __('Marital Statuses', 'empd-com') ,
		);
		$tax_settings = get_option('empd_com_tax_settings', Array());
		if (empty($tax_settings['marital_status']['hide']) || (!empty($tax_settings['marital_status']['hide']) && $tax_settings['marital_status']['hide'] != 'hide')) {
			if (!empty($tax_settings['marital_status']['rewrite'])) {
				$rewrite = $tax_settings['marital_status']['rewrite'];
			} else {
				$rewrite = 'marital_status';
			}
			register_taxonomy('marital_status', array(
				'emd_employee'
			) , array(
				'hierarchical' => false,
				'labels' => $marital_status_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_marital_status',
					'edit_terms' => 'edit_marital_status',
					'delete_terms' => 'delete_marital_status',
					'assign_terms' => 'assign_marital_status'
				) ,
			));
		}
		if (!get_option('empd_com_emd_employee_terms_init')) {
			$set_tax_terms = Array(
				Array(
					'name' => __('Full Time', 'empd-com') ,
					'slug' => sanitize_title('Full Time')
				) ,
				Array(
					'name' => __('Part Time', 'empd-com') ,
					'slug' => sanitize_title('Part Time')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'employment_type');
			$set_tax_terms = Array(
				Array(
					'name' => __('Product Manager', 'empd-com') ,
					'slug' => sanitize_title('Product Manager')
				) ,
				Array(
					'name' => __('Sales Manager', 'empd-com') ,
					'slug' => sanitize_title('Sales Manager')
				) ,
				Array(
					'name' => __('Agent', 'empd-com') ,
					'slug' => sanitize_title('Agent')
				) ,
				Array(
					'name' => __('Contractor', 'empd-com') ,
					'slug' => sanitize_title('Contractor')
				) ,
				Array(
					'name' => __('Analyst', 'empd-com') ,
					'slug' => sanitize_title('Analyst')
				) ,
				Array(
					'name' => __('Developer', 'empd-com') ,
					'slug' => sanitize_title('Developer')
				) ,
				Array(
					'name' => __('Director', 'empd-com') ,
					'slug' => sanitize_title('Director')
				) ,
				Array(
					'name' => __('CEO', 'empd-com') ,
					'slug' => sanitize_title('CEO')
				) ,
				Array(
					'name' => __('President', 'empd-com') ,
					'slug' => sanitize_title('President')
				) ,
				Array(
					'name' => __('CFO', 'empd-com') ,
					'slug' => sanitize_title('CFO')
				) ,
				Array(
					'name' => __('HR Manager', 'empd-com') ,
					'slug' => sanitize_title('HR Manager')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'jobtitles');
			$set_tax_terms = Array(
				Array(
					'name' => __('Male', 'empd-com') ,
					'slug' => sanitize_title('Male')
				) ,
				Array(
					'name' => __('Female', 'empd-com') ,
					'slug' => sanitize_title('Female')
				) ,
				Array(
					'name' => __('Other', 'empd-com') ,
					'slug' => sanitize_title('Other')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'gender');
			$set_tax_terms = Array(
				Array(
					'name' => __('Single', 'empd-com') ,
					'slug' => sanitize_title('Single')
				) ,
				Array(
					'name' => __('Married', 'empd-com') ,
					'slug' => sanitize_title('Married')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'marital_status');
			update_option('empd_com_emd_employee_terms_init', true);
		}
	}
	/**
	 * Set metabox fields,labels,filters, comments, relationships if exists
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function set_filters() {
		do_action('emd_ext_class_init', $this);
		$search_args = Array();
		$filter_args = Array();
		$this->sing_label = __('Employee', 'empd-com');
		$this->plural_label = __('Employees', 'empd-com');
		$this->menu_entity = 'emd_employee';
		$this->boxes['emd_employee_info_emd_employee_0'] = array(
			'id' => 'emd_employee_info_emd_employee_0',
			'title' => __('Employee Info', 'empd-com') ,
			'app_name' => 'empd_com',
			'pages' => array(
				'emd_employee'
			) ,
			'context' => 'normal',
		);
		list($search_args, $filter_args) = $this->set_args_boxes();
		if (!post_type_exists($this->post_type) || in_array($this->post_type, Array(
			'post',
			'page'
		))) {
			self::register();
		}
		do_action('emd_set_adv_filtering', $this->post_type, $search_args, $this->boxes, $filter_args, $this->textdomain, $this->plural_label);
		$ent_map_list = get_option(str_replace('-', '_', $this->textdomain) . '_ent_map_list');
	}
	/**
	 * Initialize metaboxes
	 * @since WPAS 4.5
	 *
	 */
	public function set_metabox() {
		if (class_exists('EMD_Meta_Box') && is_array($this->boxes)) {
			foreach ($this->boxes as $meta_box) {
				new EMD_Meta_Box($meta_box);
			}
		}
	}
	/**
	 * Change content for created frontend views
	 * @since WPAS 4.0
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function change_content($content) {
		global $post;
		$layout = "";
		$this->id = $post->ID;
		add_filter('the_title', array(
			$this,
			'change_title_disable_emd_temp'
		) , 10, 2);
		if (get_post_type() == $this->post_type && is_single()) {
			ob_start();
			emd_get_template_part($this->textdomain, 'single', 'emd-employee');
			$layout = ob_get_clean();
		}
		if ($layout != "") {
			$content = $layout;
		}
		remove_filter('the_title', array(
			$this,
			'change_title_disable_emd_temp'
		) , 10, 2);
		return $content;
	}
	/**
	 * Add operations and add new submenu hook
	 * @since WPAS 4.4
	 */
	public function add_menu_link() {
		add_submenu_page(null, __('Operations', 'empd-com') , __('Operations', 'empd-com') , 'manage_operations_emd_employees', 'operations_emd_employee', array(
			$this,
			'get_operations'
		));
	}
	/**
	 * Display operations page
	 * @since WPAS 4.0
	 */
	public function get_operations() {
		if (current_user_can('manage_operations_emd_employees')) {
			$myapp = str_replace("-", "_", $this->textdomain);
			do_action('emd_operations_entity', $this->post_type, $this->plural_label, $this->sing_label, $myapp, $this->menu_entity);
		}
	}
	public function change_title_text($title) {
		$screen = get_current_screen();
		if ($this->post_type == $screen->post_type) {
			$title = __('Enter Full Name here', 'empd-com');
		}
		return $title;
	}
}
new Emd_Employee;
