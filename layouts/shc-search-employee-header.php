<?php global $search_employee_shc_count; ?><p><strong>Search results</strong></p>
<table data-toggle="table" data-search="true">
    <thead>
        <tr>
            <?php if (emd_is_item_visible('ent_employee_number', 'empd_com', 'attribute', 1)) { ?> 
            <th data-field="empno" data-sortable="true"><?php _e('EmpNo', 'empd-com'); ?></th>
            <?php
} ?> 
            <th data-field="emptitle" data-sortable="true"><?php _e('Name', 'empd-com'); ?></th>
            <?php if (emd_is_item_visible('ent_employee_email', 'empd_com', 'attribute', 1)) { ?> 
            <th data-field="empemail" data-sortable="true"><?php _e('Email', 'empd-com'); ?></th>
            <?php
} ?> <?php if (emd_is_item_visible('tax_departments', 'empd_com', 'taxonomy', 1)) { ?> 
            <th data-field="taxdpt" data-sortable="true"><?php _e('Department', 'empd-com'); ?></th>
            <?php
} ?> <?php if (emd_is_item_visible('tax_jobtitles', 'empd_com', 'taxonomy', 1)) { ?> 
            <th data-field="taxloc" data-sortable="true"><?php _e('Job Title', 'empd-com'); ?></th>
            <?php
} ?> 
        </tr>
    </thead>
    <tbody>