<?php global $search_employee_count;
$ent_attrs = get_option('empd_com_attr_list'); ?>
<tr>
    <?php if (emd_is_item_visible('ent_employee_number', 'empd_com', 'attribute')) { ?> 
    <td><a href="<?php echo get_permalink(); ?>"><?php echo esc_html(emd_mb_meta('emd_employee_number')); ?>
</a></td>
    <?php
} ?> 
    <td><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></td>
    <?php if (emd_is_item_visible('ent_employee_email', 'empd_com', 'attribute')) { ?> 
    <td><?php echo esc_html(emd_mb_meta('emd_employee_email')); ?>
</td>
    <?php
} ?> <?php if (emd_is_item_visible('tax_departments', 'empd_com', 'taxonomy')) { ?> 
    <td><?php echo emd_get_tax_vals(get_the_ID() , 'departments'); ?></td>
    <?php
} ?> <?php if (emd_is_item_visible('tax_jobtitles', 'empd_com', 'taxonomy')) { ?> 
    <td><?php echo emd_get_tax_vals(get_the_ID() , 'jobtitles'); ?></td>
    <?php
} ?> 
</tr>