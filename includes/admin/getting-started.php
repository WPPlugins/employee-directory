<?php
/**
 * Getting Started
 *
 * @package EMPD_COM
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_action('empd_com_getting_started', 'empd_com_getting_started');
/**
 * Display getting started information
 * @since WPAS 5.3
 *
 * @return html
 */
function empd_com_getting_started() {
	global $title;
	list($display_version) = explode('-', EMPD_COM_VERSION);
?>
<style>
div.comp-feature {
    font-weight: 400;
    font-size:20px;
}
.edition-com {
    display: none;
}
.green{
color: #008000;
font-size: 30px;
}
#nav-compare:before{
    content: "\f179";
}
#emd-about .nav-tab-wrapper a:before{
    position: relative;
    box-sizing: content-box;
padding: 0px 3px;
color: #4682b4;
    width: 20px;
    height: 20px;
    overflow: hidden;
    white-space: nowrap;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
font-family: dashicons;
}
#nav-getting-started:before{
content: "\f102";
}
#nav-whats-new:before{
content: "\f348";
}
#nav-resources:before{
content: "\f118";
}
#emd-about .embed-container { 
	position: relative; 
	padding-bottom: 56.25%;
	height: 0;
	overflow: hidden;
	max-width: 100%;
	height: auto;
	} 

#emd-about .embed-container iframe,
#emd-about .embed-container object,
#emd-about .embed-container embed { 
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	}
#emd-about ul li:before{
    content: "\f522";
    font-family: dashicons;
    font-size:25px;
 }
#gallery {
	margin: auto;
}
#gallery .gallery-item {
	float: left;
	margin-top: 10px;
	margin-right: 10px;
	text-align: center;
	width: 48%;
        cursor:pointer;
}
#gallery img {
	border: 2px solid #cfcfcf; 
height: 405px;  
}
#gallery .gallery-caption {
	margin-left: 0;
}
#emd-about .top{
text-decoration:none;
}
#emd-about .toc{
    background-color: #fff;
    padding: 25px;
    border: 1px solid #add8e6;
    border-radius: 8px;
}
#emd-about h3,
#emd-about h2{
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0.6em;
    margin-left: 0px;
}
#emd-about p,
#emd-about .emd-section li{
font-size:18px
}
#emd-about a.top:after{
content: "\f342";
    font-family: dashicons;
    font-size:25px;
text-decoration:none;
}
#emd-about .toc a,
#emd-about a.top{
vertical-align: top;
}
#emd-about li{
list-style-type: none;
line-height: normal;
}
#emd-about ol li {
    list-style-type: decimal;
}
#emd-about .quote{
    background: #fff;
    border-left: 4px solid #088cf9;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    margin-top: 25px;
    padding: 1px 12px;
}
#emd-about .tooltip{
    display: inline;
    position: relative;
}
#emd-about .tooltip:hover:after{
    background: #333;
    background: rgba(0,0,0,.8);
    border-radius: 5px;
    bottom: 26px;
    color: #fff;
    content: 'Click to enlarge';
    left: 20%;
    padding: 5px 15px;
    position: absolute;
    z-index: 98;
    width: 220px;
}
</style>

<?php add_thickbox(); ?>
<div id="emd-about" class="wrap about-wrap">
<div id="emd-header" style="padding:10px 0" class="wp-clearfix">
<div style="float:right"><img src="https://emd-plugins.s3.amazonaws.com/empdir-logo-250x150.gif"></div>
<div style="margin: .2em 200px 0 0;padding: 0;color: #32373c;line-height: 1.2em;font-size: 2.8em;font-weight: 400;">
<?php printf(__('Welcome to Employee Directory Community %s', 'empd-com') , $display_version); ?>
</div>

<p class="about-text">
<?php printf(__("Easy-to-use and maintain employee directory solution for your business", 'empd-com') , $display_version); ?>
</p>

<?php
	$tabs['getting-started'] = __('Getting Started', 'empd-com');
	$tabs['whats-new'] = __('What\'s New', 'empd-com');
	$tabs['resources'] = __('Resources', 'empd-com');
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'getting-started';
	echo '<h2 class="nav-tab-wrapper wp-clearfix">';
	foreach ($tabs as $ktab => $mytab) {
		$tab_url[$ktab] = esc_url(add_query_arg(array(
			'tab' => $ktab
		)));
		$active = "";
		if ($active_tab == $ktab) {
			$active = "nav-tab-active";
		}
		echo '<a href="' . esc_url($tab_url[$ktab]) . '" class="nav-tab ' . $active . '" id="nav-' . $ktab . '">' . $mytab . '</a>';
	}
	echo '</h2>';
	echo '<div class="tab-content" id="tab-getting-started"';
	if ("getting-started" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="rtop"></div><div class="toc"><h3 style="color:#0073AA;text-align:left;">Quickstart</h3><ul><li><a href="#gs-sec-90">Employee Directory Community Introduction</a></li>
<li><a href="#gs-sec-92">EMD CSV Import Export Extension helps you get your data in and out of WordPress quickly, saving you ton of time</a></li>
<li><a href="#gs-sec-117">EMD Active Directory/LDAP Extension helps bulk import and update Employee Directory data from LDAP</a></li>
<li><a href="#gs-sec-91">EMD Advanced Filters and Columns Extension for finding what's important faster</a></li>
<li><a href="#gs-sec-97">EMD vCard Extension</a></li>
<li><a href="#gs-sec-95">Employee Directory Pro - Improve internal and external communication of your organization with the best in class company directory</a></li>
<li><a href="#gs-sec-98">Employee Spotlight Pro - Beautiful, customizable employee profile management system</a></li>
<li><a href="#gs-sec-96">Campus Directory Pro - an easy to use, dynamic and searchable academic catalog integrating people, publications, courses and locations</a></li>
</ul></div><div class="quote">
<p class="about-description">The secret of getting ahead is getting started - Mark Twain</p>
</div>
<div class="getting-started emd-section changelog getting-started getting-started-90" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-90"></div><h2>Employee Directory Community Introduction</h2><div class="emd-yt" data-youtube-id="z_vhhJz_uEc" data-ratio="16:9">loading...</div><div class="sec-desc"><p>Watch Employee Directory Community introduction video to learn about the plugin features and configuration.</p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-92" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-92"></div><h2>EMD CSV Import Export Extension helps you get your data in and out of WordPress quickly, saving you ton of time</h2><div class="emd-yt" data-youtube-id="tJDQbU3jS0c" data-ratio="16:9">loading...</div><p style="font-weight:bold">This extension is included in the pro edition.</p><div class="sec-desc"><p>EMD CSV Import Export Extension helps bulk import, export, update entries from/to CSV files. You can also reset(delete) all data and start over again without modifying database. The export feature is also great for backups and archiving old or obsolete data.</p>
<p><a href="https://emdplugins.com/plugins/emd-csv-import-export-extension/?pk_campaign=emdimpexp-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-117" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-117"></div><h2>EMD Active Directory/LDAP Extension helps bulk import and update Employee Directory data from LDAP</h2><div class="emd-yt" data-youtube-id="onWfeZHLGzo" data-ratio="16:9">loading...</div><div class="sec-desc"><p>EMD Active Directory/LDAP Extension helps bulk importing and updating Employee Directory data by visually mapping LDAP fields. The imports/updates can scheduled on desired intervals using WP Cron.</p>
<p><a href="https://emdplugins.com/plugins/emd-active-directory-ldap-extension/?pk_campaign=emdldap-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-91" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-91"></div><h2>EMD Advanced Filters and Columns Extension for finding what's important faster</h2><div class="emd-yt" data-youtube-id="JDIHIibWyR0" data-ratio="16:9">loading...</div><p style="font-weight:bold">This extension is included in the pro edition.</p><div class="sec-desc"><p>EMD Advanced Filters and Columns Extension for Employee Directory Community edition helps you:</p><ul><li>Filter entries quickly to find what you're looking for</li><li>Save your frequently used filters so you do not need to create them again</li><li>Sort quote request columns to see what's important faster</li><li>Change the display order of columns </li>
<li>Enable or disable columns for better and cleaner look </li><li>Export search results to PDF or CSV for custom reporting</li></ul><div style="margin:25px"><a href="https://emdplugins.com/plugins/emd-advanced-filters-and-columns-extension/?pk_campaign=emd-afc-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-97" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-97"></div><h2>EMD vCard Extension</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-97" href="https://emdsnapshots.s3.amazonaws.com/emd_vcard_empd_com.png"><img src="https://emdsnapshots.s3.amazonaws.com/emd_vcard_empd_com.png"></a></div></div><div class="sec-desc"><p>Provides ability to download employee profile information as vCard - file format standard for electronic business cards</p>
<div style="margin:25px"><a href="https://emdplugins.com/plugins/emd-vcard-extension/?pk_campaign=emd-vcard-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-95" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-95"></div><h2>Employee Directory Pro - Improve internal and external communication of your organization with the best in class company directory</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-95" href="https://emdsnapshots.s3.amazonaws.com/montage_emp_pro_50.png"><img src="https://emdsnapshots.s3.amazonaws.com/montage_emp_pro_50.png"></a></div></div><div class="sec-desc"><p>Employee Directory Pro edition provides an easy to use and maintain centralized employee directory solution complete with multiple advanced search forms, organization chart, employee profile, and company event pages.</p>
<ul>
<li>Search employees by names, job titles, skills, direct reports, employee numbers etc.</li>
<li>Provide ability to limit access to search forms by logged-in users only</li>
<li>Display featured employees, recent hires, and the current week's birthdays</li>
<li>Display and search though employees using organizational chart</li>
<li>Display company events by location and/or groups in their own pages and in event calendar</li>
<li>Filter through employees with an advanced grid complete with searching, sorting, exporting into multiple file formats</li>
<li>Showcase your employees in their own pages with a primary address map, status info and more</li>
<li>Designed for intranets with no access to internet</li>
</ul>
<div style="margin:25px"><a href="https://emdplugins.com/plugins/employee-directory-professional/?pk_campaign=empd-pro-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-98" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-98"></div><h2>Employee Spotlight Pro - Beautiful, customizable employee profile management system</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-98" href="https://emdsnapshots.s3.amazonaws.com/montage_empslight-pro.jpg"><img src="https://emdsnapshots.s3.amazonaws.com/montage_empslight-pro.jpg"></a></div></div><div class="sec-desc"><p>Showcase your organization highlighting individuals or groups with beautiful layouts.</p>
<ul>
<li>Beautiful, responsive employee profile management with 100+ unique displays</li>
<li>Power up member, staff bios with custom fields</li>
<li>Display optional 5 social links for each member</li>
<li>Profile owners can update profile pages</li>
<li>Showcase featured members or staff, new hires and upcoming birthdays on your site's sidebar</li>
<li>Customizable employee profile pages.</li>
<li>Integrated advanced CSV importer/exporter</li>
<li>Integrated Visual Shortcode Builder for unique displays</li>
</ul>
<div style="margin:25px"><a href="https://emdplugins.com/plugins/employee-spotlight-pro/?pk_campaign=espotlight-pro-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="getting-started emd-section changelog getting-started getting-started-96" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-96"></div><h2>Campus Directory Pro - an easy to use, dynamic and searchable academic catalog integrating people, publications, courses and locations</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-96" href="https://emdsnapshots.s3.amazonaws.com/montage_campusdir_pro_1102.png"><img src="https://emdsnapshots.s3.amazonaws.com/montage_campusdir_pro_1102.png"></a></div></div><div class="sec-desc"><p>Campus Directory Pro provides an easy to use, dynamic and searchable academic catalog that meets modern web standards while matching the style and graphics of your organization's brand. Designed and developed for higher education institutions.</p>
<ul>
<li>Allow users find information quickly by offering multi-dimensional search by people, publications, courses and locations</li>
<li>Integrate and display multiple content segments seamlessly</li>
<li>Ensure brand consistency with an online catalog that matches your organization’s website</li>
<li>Allow access to information from anywhere and any device </li>
<li>Extensive taxonomy based classification system for people, publications, courses and location</li>
<li>Extensive customization options for frontend and backend through plugin settings</li>
<li>Extend your academic catalog with unlimited, searchable custom fields</li> 
<li>Integrate with external systems with advanced bulk import,export and sync functionality</li>
</ul>
<div style="margin:25px"><a href="https://emdplugins.com/plugins/campus-directory-professional/?pk_campaign=campdir-pro-buybtn&pk_kwd=empd-com-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px">

<?php echo '</div>';
	echo '<div class="tab-content" id="tab-whats-new"';
	if ("whats-new" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<p class="about-description">Employee Directory Community V3.6.2 offers many new features, bug fixes and improvements.</p>


<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">3.6.2 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-227" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
WP Sessions security vulnerability</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">3.6.1 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-156" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
minor fixes</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-151" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Configured to work with EMD Active Directory/LDAP extension</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">3.6.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-93" style="margin:0">
<h3 style="font-size:18px;" class="remove"><div  style="font-size:110%;color:#ff4444"><span class="dashicons dashicons-editor-removeformatting"></span> REMOVE</div>
Image border color for employee photos. This can be easily done in CSS.</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-92" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added a getting started page for plugin introduction, tips and resources</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-91" style="margin:0">
<h3 style="font-size:18px;" class="remove"><div  style="font-size:110%;color:#ff4444"><span class="dashicons dashicons-editor-removeformatting"></span> REMOVE</div>
Global parameter in changing image border color for employee photos was deleted. This can be easily done in CSS.</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-90" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Modified single employee page to a new look</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<?php echo '</div>';
	echo '<div class="tab-content" id="tab-resources"';
	if ("resources" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="ptop"></div><div class="toc"><h3 style="color:#0073AA;text-align:left;">Upgrade your game for better results</h3><ul><li><a href="#gs-sec-89">Extensive documentation is available</a></li>
<li><a href="#gs-sec-94">How to customize Employee Directory Community</a></li>
<li><a href="#gs-sec-93">How to resolve theme related issues</a></li>
<li><a href="#gs-sec-118">Managing Organizational Reporting Hierarchy using Employee Directory Pro</a></li>
</ul></div><div class="emd-section changelog resources resources-89" style="margin:0"><div style="height:40px" id="gs-sec-89"></div><h2>Extensive documentation is available</h2><div id="gallery" class="wp-clearfix"></div><div class="sec-desc"><a href="https://docs.emdplugins.com/docs/employee-directory-community">Employee Directory Community Documentation</a></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="emd-section changelog resources resources-94" style="margin:0"><div style="height:40px" id="gs-sec-94"></div><h2>How to customize Employee Directory Community</h2><div class="emd-yt" data-youtube-id="4wcFcIfHhPA" data-ratio="16:9">loading...</div><div class="sec-desc"><p><strong><span class="dashicons dashicons-arrow-up-alt"></span> Watch the customization video to familiarize yourself with the customization options. </strong>. The video shows one of our plugins as an example. The concepts are the same and all our plugins have the same settings.</p>
<p>Employee Directory Community is designed and developed using <a href="https://wpappstudio.com">WP App Studio (WPAS) Professional WordPress Development platform</a>. All WPAS plugins come with extensive customization options from plugin settings without changing theme template files. Some of the customization options are listed below:</p>
<ul>
	<li>Enable or disable all fields, taxonomies and relationships from backend and/or frontend</li>
        <li>Use the default EMD or theme templating system</li>
	<li>Set slug of any entity and/or archive base slug</li>
	<li>Set the page template of any entity, taxonomy and/or archive page to sidebar on left, sidebar on right or no sidebar (full width)</li>
	<li>Hide the previous and next post links on the frontend for single posts</li>
	<li>Hide the page navigation links on the frontend for archive posts</li>
	<li>Display or hide any custom field</li>
	<li>Display any sidebar widget on plugin pages using EMD Widget Area</li>
	<li>Set custom CSS rules for all plugin pages including plugin shortcodes</li>
</ul>
<div class="quote">
<p>If your customization needs are more complex, you’re unfamiliar with code/templates and resolving potential conflicts, we strongly suggest you to <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=empd-com-hireme-custom&ticket_topic=pre-sales-questions">hire us</a>, we will get your site up and running in no time.
</p>
</div></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="emd-section changelog resources resources-93" style="margin:0"><div style="height:40px" id="gs-sec-93"></div><h2>How to resolve theme related issues</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-93" href="https://emdsnapshots.s3.amazonaws.com/emd_templating_system.png"><img src="https://emdsnapshots.s3.amazonaws.com/emd_templating_system.png"></a></div></div><div class="sec-desc"><p>If your theme is not coded based on WordPress theme coding standards, does have an unorthodox markup or its style.css is messing up how Employee Directory Community pages look and feel, you will see some unusual changes on your site such as sidebars not getting displayed where they are supposed to or random text getting displayed on headers etc. after plugin activation.</p>
<p>The good news is Employee Directory Community plugin is designed to minimize theme related issues by providing two distinct templating systems:</p>
<ul>
<li>The EMD templating system is the default templating system where the plugin uses its own templates for plugin pages.</li>
<li>The theme templating system where Employee Directory Community uses theme templates for plugin pages.</li>
</ul>
<p>The EMD templating system is the recommended option. If the EMD templating system does not work for you, you need to check "Disable EMD Templating System" option at Settings > Tools tab and switch to theme based templating system.</p>
<p>Please keep in mind that when you disable EMD templating system, you loose the flexibility of modifying plugin pages without changing theme template files.</p>
<p>If none of the provided options works for you, you may still fix theme related conflicts following the steps in <a href="https://docs.emdplugins.com/docs/employee-directory-community">Employee Directory Community Documentation - Resolving theme related conflicts section.</a></p>

<div class="quote">
<p>If you’re unfamiliar with code/templates and resolving potential conflicts, <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=raq-hireme&ticket_topic=pre-sales-questions"> do yourself a favor and hire us</a>. Sometimes the cost of hiring someone else to fix things is far less than doing it yourself. We will get your site up and running in no time.</p>
</div></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="emd-section changelog resources resources-118" style="margin:0"><div style="height:40px" id="gs-sec-118"></div><h2>Managing Organizational Reporting Hierarchy using Employee Directory Pro</h2><div class="emd-yt" data-youtube-id="DVR09o3eerI" data-ratio="16:9">loading...</div><div class="sec-desc"><p>Employees get hired, get promoted, leave company or simple change departments. Using text based or static charting methods to visualize the employee lifecycle create maintenance nightmare or simply fall short of modern communication methods available to businesses.</p>
<p>Employee Directory Pro solves this issue by producing organization charts and displaying employee-manager relationships in employee profiles on the fly, helping internal and external communication.</p>
<p><a href="https://emdplugins.com/articles/managing-organizational-reporting-hierarchy/?pk_campaign=emdldap-buybtn&pk_kwd=empd-com-resources" target="_blank">Read More</a></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px">
<?php echo '</div>'; ?>

<?php echo '</div>';
}
