<?php $real_post = $post;
$ent_attrs = get_option('empd_com_attr_list');
?>
<div style="position:relative" class="emd-container">
<div style="padding-bottom:10px;clear:both" id="modified-info-block" class=" text-right">
    <div class="textSmall text-muted"><?php _e('Last modified by', 'empd-com'); ?> <?php echo get_the_modified_author(); ?> - <?php echo human_time_diff(strtotime(get_the_modified_date() . " " . get_the_modified_time()) , current_time('timestamp')); ?> <?php _e('ago', 'empd-com'); ?></div>
</div>
<div class="panel panel-default" >
    <div class="panel-heading" style="position:relative; ">
        <div class="panel-title">
            <div class='single-title font-bold header'>
                <h2 class='entry-title'>
                    <span class="emd_employee-title"><?php echo get_the_title(); ?></span>
                </h2>
            </div>
        </div>
    </div>
    <div class="panel-body" style="clear:both">
        <div class="single-well well emd-employee">
            <div class="row">
                <div class="col-sm-<?php if (emd_is_item_visible('ent_employee_photo', 'empd_com', 'attribute')) {
	echo '4 well-left';
} else {
	echo ' hidden';
} ?>">
                    <div class="slcontent emdbox">
                        <?php if (emd_is_item_visible('ent_employee_photo', 'empd_com', 'attribute')) { ?>
                        <div class="img-gallery segment-block ent-employee-photo"><a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink(); ?>"><?php if (get_post_meta($post->ID, 'emd_employee_photo')) {
		$sval = get_post_meta($post->ID, 'emd_employee_photo');
		$thumb = wp_get_attachment_image_src($sval[0], 'thumbnail');
		echo '<img class="emd-img thumb" src="' . $thumb[0] . '" width="' . $thumb[1] . '" height="' . $thumb[2] . '" alt="' . get_post_meta($sval[0], '_wp_attachment_image_alt', true) . '"/>';
	} ?></a></div>
                        <?php
} ?>
                    </div>
                </div>
                <div class="col-sm-<?php if (emd_is_item_visible('ent_employee_photo', 'empd_com', 'attribute')) {
	echo '8 well-right';
} else {
	echo '12 well-right';
} ?>">
                    <div class="srcontent emdbox">
                        <?php if (emd_is_item_visible('ent_employee_phone', 'empd_com', 'attribute')) { ?>
                        <div class="single-content segment-block ent-employee-phone"><i class="fa fa-phone fa-fw text-muted" aria-hidden="true"></i><a href="tel:<?php echo esc_html(emd_mb_meta('emd_employee_phone')); ?>
"><?php echo esc_html(emd_mb_meta('emd_employee_phone')); ?>
</a></div>
                        <?php
} ?><?php if (emd_is_item_visible('ent_employee_extension', 'empd_com', 'attribute')) { ?>
                        <div class="single-content segment-block ent-employee-extension"><i class="fa fa-times fa-fw text-muted" aria-hidden="true"></i><?php echo esc_html(emd_mb_meta('emd_employee_extension')); ?>
</div>
                        <?php
} ?><?php if (emd_is_item_visible('ent_employee_mobile', 'empd_com', 'attribute')) { ?>
                        <div class="single-content segment-block ent-employee-mobile"><i class="fa fa-mobile fa-fw text-muted" aria-hidden="true"></i><?php echo esc_html(emd_mb_meta('emd_employee_mobile')); ?>
</div>
                        <?php
} ?><?php if (emd_is_item_visible('ent_employee_email', 'empd_com', 'attribute')) { ?>
                        <div class="single-content segment-block ent-employee-email"><i class="fa fa-envelope fa-fw text-muted" aria-hidden="true"></i><a href="mailto:<?php echo antispambot(esc_html(emd_mb_meta('emd_employee_email'))); ?>"><?php echo antispambot(esc_html(emd_mb_meta('emd_employee_email'))); ?></a></div>
                        <?php
} ?><?php if (emd_is_item_visible('ent_employee_primary_address', 'empd_com', 'attribute')) { ?>
                        <div class="single-content segment-block ent-employee-primary-address"><i class="fa fa-map-marker fa-fw text-muted" aria-hidden="true"></i><?php echo esc_html(emd_mb_meta('emd_employee_primary_address')); ?>
</div>
                        <?php
} ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-links clearfix">
            <div class="social" style="margin:15px 0;">
                <?php if (emd_is_item_visible('ent_employee_facebook', 'empd_com', 'attribute')) { ?>
                <div style="margin:0;display:inline-block;padding:2px;">
                    <a class="social-icon facebook animate" href="<?php echo esc_html(emd_mb_meta('emd_employee_facebook')); ?>
"><i class="fa fa-facebook fa-fw" style="font-size:20px;"></i></a>
                </div>
                <?php
} ?><?php if (emd_is_item_visible('ent_employee_google', 'empd_com', 'attribute')) { ?>
                <div style="margin:0;display:inline-block;padding:2px;">
                    <a class="social-icon google animate" href="<?php echo esc_html(emd_mb_meta('emd_employee_google')); ?>
"><i class="fa fa-google fa-fw" style="font-size:20px;"></i></a>
                </div>
                <?php
} ?><?php if (emd_is_item_visible('ent_employee_twitter', 'empd_com', 'attribute')) { ?>
                <div style="margin:0;display:inline-block;padding:2px;">
                    <a class="social-icon twitter animate" href="<?php echo esc_html(emd_mb_meta('emd_employee_twitter')); ?>
"><i class="fa fa-twitter fa-fw" style="font-size:20px;"></i></a>
                </div>
                <?php
} ?><?php if (emd_is_item_visible('ent_employee_linkedin', 'empd_com', 'attribute')) { ?>
                <div style="margin:0;display:inline-block;padding:2px;">
                    <a class="social-icon linkedin animate" href="<?php echo esc_html(emd_mb_meta('emd_employee_linkedin')); ?>
"><i class="fa fa-linkedin fa-fw" style="font-size:20px;"></i></a>
                </div>
                <?php
} ?><?php if (emd_is_item_visible('ent_employee_github', 'empd_com', 'attribute')) { ?>
                <div style="margin:0;display:inline-block;padding:2px;">
                    <a class="social-icon github animate" href="<?php echo esc_html(emd_mb_meta('emd_employee_github')); ?>
"><i class="fa fa-github fa-fw" style="font-size:20px;"></i></a>
                </div>
                <?php
} ?>
                <div style='margin:0;display:inline-block'><?php do_action('emd_vcard', 'empd_com', 'emd_employee', $post->ID); ?></div>
            </div>
        </div>
        <div class="tab-container wpastabcontainer" style="padding:0 20px 40px;">
            <ul class="nav nav-tabs" role="tablist" style="margin: 20px 0px 22px;visibility: visible;padding-bottom:0px;">
                <li class=" active "><a id="bio-tablink" href="#bio" role="tab" data-toggle="tab"><?php _e('Bio', 'empd-com'); ?></a></li>
                <li><a id="details-tablink" href="#details" role="tab" data-toggle="tab"><?php _e('Details', 'empd-com'); ?></a></li>
            </ul>
            <div class="tab-content wpastabcontent">
                <div class="tab-pane fade in active" id="bio">
                    <?php if (emd_is_item_visible('content', 'empd_com', 'attribute')) { ?>
                    <div class="single-content content"><?php echo $post->post_content; ?></div>
                    <?php
} ?>
                </div>
                <div class="tab-pane fade in " id="details">
                    <?php if (emd_is_item_visible('ent_employee_number', 'empd_com', 'attribute')) { ?>
                    <div class="segment-block ent-employee-number">
                        <div data-has-attrib="false" class="row">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Employee No', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo esc_html(emd_mb_meta('emd_employee_number')); ?>
</span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('ent_employee_hiredate', 'empd_com', 'attribute')) { ?>
                    <div class="segment-block ent-employee-hiredate">
                        <div data-has-attrib="false" class="row">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Hire Date', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo date_i18n(get_option('date_format') , strtotime(emd_mb_meta('emd_employee_hiredate'))); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('ent_employee_birthday', 'empd_com', 'attribute')) { ?>
                    <div class="segment-block ent-employee-birthday">
                        <div data-has-attrib="false" class="row">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Birthday', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo mysql2date('F j', emd_translate_date_format($ent_attrs['emd_employee']['emd_employee_birthday'], emd_mb_meta('emd_employee_birthday'))); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('tax_departments', 'empd_com', 'taxonomy')) { ?>
                    <div class="segment-block tax-departments">
                        <div class="row" hasattrib="">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Department', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'departments'); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('tax_jobtitles', 'empd_com', 'taxonomy')) { ?>
                    <div class="segment-block tax-jobtitles">
                        <div class="row" hasattrib="">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Job Title', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'jobtitles'); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('tax_gender', 'empd_com', 'taxonomy')) { ?>
                    <div class="segment-block tax-gender">
                        <div class="row" hasattrib="">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Gender', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'gender'); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('tax_marital_status', 'empd_com', 'taxonomy')) { ?>
                    <div class="segment-block tax-marital-status">
                        <div class="row" hasattrib="">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Marital Status', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'marital_status'); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('tax_employment_type', 'empd_com', 'taxonomy')) { ?>
                    <div class="segment-block tax-employment-type">
                        <div class="row" hasattrib="">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Employment Type', 'empd-com'); ?></span></div>
                            <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'employment_type'); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php $cust_fields = get_metadata('post', get_the_ID());
$real_cust_fields = Array();
$ent_map_list = get_option('empd_com_ent_map_list', Array());
foreach ($cust_fields as $ckey => $cval) {
	if (empty($ent_attrs['emd_employee'][$ckey]) && !preg_match('/^(_|wpas_|emd_)/', $ckey)) {
		$cust_key = str_replace('-', '_', sanitize_title($ckey));
		if (empty($ent_map_list) || (!empty($ent_map_list) && empty($ent_map_list['emd_employee']['cust_fields'][$cust_key]))) {
			$real_cust_fields[$ckey] = $cval;
		}
	}
}
if (!empty($real_cust_fields)) {
	$fcount = 0;
	foreach ($real_cust_fields as $rkey => $rval) {
		$val = implode($rval, " ");
		$fcount++;
?><div id='cust-field-<?php echo $fcount; ?>'>
                    <div class="segment-block emd-employee-custom-fields">
                        <div class="row">
                            <div class="col-sm-6 "><span class="segtitle"><?php echo $rkey; ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo $val; ?></span></div>
                        </div>
                    </div>
                    </div><?php
	}
}
?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer"></div>
</div>
</div><!--container-end-->