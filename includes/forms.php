<?php
/**
 * Setup and Process submit and search forms
 * @package EMPD_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (is_admin()) {
	add_action('wp_ajax_nopriv_emd_check_unique', 'emd_check_unique');
}
if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
	add_filter('posts_where', 'emd_builtin_posts_where', 10, 2);
}
add_action('init', 'empd_com_form_shortcodes', -2);
/**
 * Start session and setup upload idr and current user id
 * @since WPAS 4.0
 *
 */
function empd_com_form_shortcodes() {
	global $file_upload_dir;
	$upload_dir = wp_upload_dir();
	$file_upload_dir = $upload_dir['basedir'];
	if (!empty($_POST['emd_action'])) {
		if ($_POST['emd_action'] == 'empd_com_user_login' && wp_verify_nonce($_POST['emd_login_nonce'], 'emd-login-nonce')) {
			emd_process_login($_POST, 'empd_com');
		} elseif ($_POST['emd_action'] == 'empd_com_user_register' && wp_verify_nonce($_POST['emd_register_nonce'], 'emd-register-nonce')) {
			emd_process_register($_POST, 'empd_com');
		}
	}
}
add_shortcode('search_employees', 'empd_com_process_search_employees');
/**
 * Set each form field(attr,tax and rels) and render form
 *
 * @since WPAS 4.0
 *
 * @return object $form
 */
function empd_com_set_search_employees($atts) {
	global $file_upload_dir;
	$show_captcha = 0;
	$form_variables = get_option('empd_com_glob_forms_list');
	$form_init_variables = get_option('empd_com_glob_forms_init_list');
	if (!empty($atts['set'])) {
		$set_arrs = emd_parse_set_filter($atts['set']);
	}
	if (!empty($form_variables['search_employees']['captcha'])) {
		switch ($form_variables['search_employees']['captcha']) {
			case 'never-show':
				$show_captcha = 0;
			break;
			case 'show-always':
				$show_captcha = 1;
			break;
			case 'show-to-visitors':
				if (is_user_logged_in()) {
					$show_captcha = 0;
				} else {
					$show_captcha = 1;
				}
			break;
		}
	}
	$req_hide_vars = emd_get_form_req_hide_vars('empd_com', 'search_employees');
	$form = new Zebra_Form('search_employees', 0, 'POST', '', array(
		'class' => 'form-container wpas-form wpas-form-stacked',
		'session_obj' => EMPD_COM()->session
	));
	$csrf_storage_method = (isset($form_variables['search_employees']['csrf']) ? $form_variables['search_employees']['csrf'] : $form_init_variables['search_employees']['csrf']);
	if ($csrf_storage_method == 0) {
		$form->form_properties['csrf_storage_method'] = false;
	}
	if (!in_array('emd_employee_number', $req_hide_vars['hide'])) {
		//text
		$form->add('label', 'label_emd_employee_number', 'emd_employee_number', __('Employee No', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md form-control',
			'placeholder' => __('Employee No', 'empd-com')
		);
		if (!empty($_GET['emd_employee_number'])) {
			$attrs['value'] = sanitize_text_field($_GET['emd_employee_number']);
		} elseif (!empty($set_arrs['attr']['emd_employee_number'])) {
			$attrs['value'] = $set_arrs['attr']['emd_employee_number'];
		}
		$obj = $form->add('text', 'emd_employee_number', '', $attrs);
		$zrule = Array(
			'dependencies' => array() ,
			'alphanumeric' => array(
				'_',
				'error',
				__('Employee No: Letters, numbers, and underscores only please')
			) ,
		);
		if (in_array('emd_employee_number', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Employee No is required', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('blt_title', $req_hide_vars['hide'])) {
		//text
		$form->add('label', 'label_blt_title', 'blt_title', __('Full Name', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md form-control',
			'placeholder' => __('Full Name', 'empd-com')
		);
		if (!empty($_GET['blt_title'])) {
			$attrs['value'] = sanitize_text_field($_GET['blt_title']);
		} elseif (!empty($set_arrs['attr']['blt_title'])) {
			$attrs['value'] = $set_arrs['attr']['blt_title'];
		}
		$obj = $form->add('text', 'blt_title', '', $attrs);
		$zrule = Array();
		if (in_array('blt_title', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Full Name is required', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('emd_employee_email', $req_hide_vars['hide'])) {
		//text
		$form->add('label', 'label_emd_employee_email', 'emd_employee_email', __('Email', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md form-control',
			'placeholder' => __('Email', 'empd-com')
		);
		if (!empty($_GET['emd_employee_email'])) {
			$attrs['value'] = sanitize_email($_GET['emd_employee_email']);
		} elseif (!empty($set_arrs['attr']['emd_employee_email'])) {
			$attrs['value'] = $set_arrs['attr']['emd_employee_email'];
		}
		$obj = $form->add('text', 'emd_employee_email', '', $attrs);
		$zrule = Array(
			'dependencies' => array() ,
			'email' => array(
				'error',
				__('Email: Please enter a valid email address', 'empd-com')
			) ,
		);
		if (in_array('emd_employee_email', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Email is required', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('jobtitles', $req_hide_vars['hide'])) {
		$form->add('label', 'label_jobtitles', 'jobtitles', __('Job Title', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['jobtitles'])) {
			$attrs['value'] = sanitize_text_field($_GET['jobtitles']);
		} elseif (!empty($set_arrs['tax']['jobtitles'])) {
			$attrs['value'] = $set_arrs['tax']['jobtitles'];
		}
		$obj = $form->add('selectadv', 'jobtitles[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "empd-com") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('jobtitles', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('jobtitles', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Job Title is required!', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('departments', $req_hide_vars['hide'])) {
		$form->add('label', 'label_departments', 'departments', __('Department', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['departments'])) {
			$attrs['value'] = sanitize_text_field($_GET['departments']);
		} elseif (!empty($set_arrs['tax']['departments'])) {
			$attrs['value'] = $set_arrs['tax']['departments'];
		}
		$obj = $form->add('selectadv', 'departments[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "empd-com") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('departments', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('departments', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Department is required!', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('gender', $req_hide_vars['hide'])) {
		$form->add('label', 'label_gender', 'gender', __('Gender', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['gender'])) {
			$attrs['value'] = sanitize_text_field($_GET['gender']);
		} elseif (!empty($set_arrs['tax']['gender'])) {
			$attrs['value'] = $set_arrs['tax']['gender'];
		}
		$obj = $form->add('selectadv', 'gender[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "empd-com") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('gender', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('gender', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Gender is required!', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('marital_status', $req_hide_vars['hide'])) {
		$form->add('label', 'label_marital_status', 'marital_status', __('Marital Status', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['marital_status'])) {
			$attrs['value'] = sanitize_text_field($_GET['marital_status']);
		} elseif (!empty($set_arrs['tax']['marital_status'])) {
			$attrs['value'] = $set_arrs['tax']['marital_status'];
		}
		$obj = $form->add('selectadv', 'marital_status[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "empd-com") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('marital_status', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('marital_status', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Marital Status is required!', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('employment_type', $req_hide_vars['hide'])) {
		$form->add('label', 'label_employment_type', 'employment_type', __('Employment Type', 'empd-com') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['employment_type'])) {
			$attrs['value'] = sanitize_text_field($_GET['employment_type']);
		} elseif (!empty($set_arrs['tax']['employment_type'])) {
			$attrs['value'] = $set_arrs['tax']['employment_type'];
		}
		$obj = $form->add('selectadv', 'employment_type[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "empd-com") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('employment_type', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('employment_type', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Employment Type is required!', 'empd-com')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	$cust_fields = Array();
	$cust_fields = apply_filters('emd_get_cust_fields', $cust_fields, 'emd_employee');
	foreach ($cust_fields as $ckey => $clabel) {
		if (!in_array($ckey, $req_hide_vars['hide'])) {
			$form->add('label', 'label_' . $ckey, $ckey, $clabel, array(
				'class' => 'control-label'
			));
			$obj = $form->add('text', $ckey, '', array(
				'class' => 'input-md form-control',
				'placeholder' => $clabel
			));
			if (in_array($ckey, $req_hide_vars['req'])) {
				$zrule = Array(
					'required' => array(
						'error',
						$clabel . __(' is required', 'empd-com')
					)
				);
				$obj->set_rule($zrule);
			}
		}
	}
	$form->assign('show_captcha', $show_captcha);
	if ($show_captcha == 1) {
		//Captcha
		$form->add('captcha', 'captcha_image', 'captcha_code', '', '<span style="font-weight:bold;" class="refresh-txt">Refresh</span>', 'refcapt');
		$form->add('label', 'label_captcha_code', 'captcha_code', __('Please enter the characters with black color.', 'empd-com'));
		$obj = $form->add('text', 'captcha_code', '', array(
			'placeholder' => __('Code', 'empd-com')
		));
		$obj->set_rule(array(
			'required' => array(
				'error',
				__('Captcha is required', 'empd-com')
			) ,
			'captcha' => array(
				'error',
				__('Characters from captcha image entered incorrectly!', 'empd-com')
			)
		));
	}
	$form->add('submit', 'singlebutton_search_employees', '' . __('Search', 'empd-com') . ' ', array(
		'class' => 'wpas-button wpas-juibutton-secondary wpas-button-large btn-block col-md-12 col-lg-12 col-xs-12 col-sm-12'
	));
	return $form;
}
/**
 * Process each form and show error or success
 *
 * @since WPAS 4.0
 *
 * @return html
 */
function empd_com_process_search_employees($atts) {
	$show_form = 1;
	$access_views = get_option('empd_com_access_views', Array());
	if (!current_user_can('view_search_employees') && !empty($access_views['forms']) && in_array('search_employees', $access_views['forms'])) {
		$show_form = 0;
	}
	$form_init_variables = get_option('empd_com_glob_forms_init_list');
	$form_variables = get_option('empd_com_glob_forms_list');
	if ($show_form == 1) {
		if (!empty($form_init_variables['search_employees']['login_reg'])) {
			$show_login_register = (isset($form_variables['search_employees']['login_reg']) ? $form_variables['search_employees']['login_reg'] : $form_init_variables['search_employees']['login_reg']);
			if (!is_user_logged_in() && $show_login_register != 'none') {
				do_action('emd_show_login_register_forms', 'empd_com', 'search_employees', $show_login_register);
				return;
			}
		}
		wp_enqueue_script('wpas-jvalidate-js');
		wp_enqueue_style('wpasui');
		wp_enqueue_style('jq-css');
		wp_enqueue_style('search-employees-forms');
		wp_enqueue_script('search-employees-forms-js');
		wp_enqueue_style('view-search-employee-cdn');
		wp_enqueue_style('empd-com-allview-css');
		empd_com_enq_custom_css();
		do_action('emd_ext_form_enq', 'empd_com', 'search_employees');
		$noresult_msg = (isset($form_variables['search_employees']['noresult_msg']) ? $form_variables['search_employees']['noresult_msg'] : $form_init_variables['search_employees']['noresult_msg']);
		return emd_search_php_form('search_employees', 'empd_com', 'emd_employee', $noresult_msg, 'search_employee', $atts);
	} else {
		$noaccess_msg = (isset($form_variables['search_employees']['noaccess_msg']) ? $form_variables['search_employees']['noaccess_msg'] : $form_init_variables['search_employees']['noaccess_msg']);
		return "<div class='alert alert-info not-authorized'>" . $noaccess_msg . "</div>";
	}
}
