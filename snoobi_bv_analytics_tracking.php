<?php

/*
Plugin Name: Snoobi BV Analytics tracking
Description: Snoobi BV Analytics tracking plugin
Version: 0.1
Copyright: 2015 Snoobi B.V.
License: GPLv2

Copyright (C) 2015 Snoobi B.V.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

function snoobianalyticsplugin_footer() {
	$string=sprintf("
<!-- Snoobi BV Analytics tracking plugin v0.2 -->
<script type='text/javascript'>
var _saq = _saq || [];
(function() {
var account = '%s';
var page_name = '';
var section = '';
var snbscript = document.createElement('script');
snbscript.type = 'text/javascript';
snbscript.async= true;   
snbscript.src = ('https:' == document.location.protocol ? 'https://' : 'http://')
+ 'eu1.snoobi.com/snoop.php?tili=' + account
+ '&page_name=' + page_name
+ '&section=' + section %s;  
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(snbscript, s);
})();
</script>
",
	get_option('snoobianalyticsplugin_snoobi_id'),
	get_option('snoobianalyticsplugin_cookies')=='off'? "+'&cookies=false'":''
);

	print($string);
}

function snoobianalyticsplugin_admin_add_page() {
	#title, menutitle, capability, slug, func
	add_options_page('Snoobi BV Analytics tracking Plugin Page', 'Snoobi Menu', 'manage_options', 'snoobipluginoptions', 'snoobianalyticsplugin_options_page');
}

function snoobianalyticsplugin_init() {
	register_setting( 'snoobi_opts', 'snoobianalyticsplugin_snoobi_id' );
	register_setting( 'snoobi_opts', 'snoobianalyticsplugin_location' );
	register_setting( 'snoobi_opts', 'snoobianalyticsplugin_cookies' );

	add_settings_section(
		'sec1', // ID
		'My Snoobi Analytics Settings', // Title
		'snoobianalyticsplugin_sec1' , // Callback
		'snoobipluginoptions' // Page
	);  
	add_settings_field(
		'snoobianalyticsplugin_snoobi_id', // ID
		'Snoobi Account ID', // Title 
		'snoobianalyticsplugin_snoobi_id_callback', // Callback
		'snoobipluginoptions',
		'sec1'
	);      
	add_settings_field(
		'snoobianalyticsplugin_location', // ID
		'Location of tracking code', // Title 
		'snoobianalyticsplugin_location_callback', // Callback
		'snoobipluginoptions',
		'sec1'
	);      
	add_settings_field(
		'snoobianalyticsplugin_cookies', // ID
		'Cookies', // Title 
		'snoobianalyticsplugin_cookies_callback', // Callback
		'snoobipluginoptions',
		'sec1'
	);      
}

function snoobianalyticsplugin_sec1() { }

function snoobianalyticsplugin_snoobi_id_callback() {
        printf(
            '<input type="text" id="snoobianalyticsplugin_snoobi_id" name="snoobianalyticsplugin_snoobi_id" value="%s" />',
			get_option('snoobianalyticsplugin_snoobi_id')
        );
}

function snoobianalyticsplugin_location_callback() {
        printf(
            '<select id="snoobianalyticsplugin_location" name="snoobianalyticsplugin_location">
				<option value="footer" %s>footer
				<option value="header" %s>header
			</select>
			',
			get_option('snoobianalyticsplugin_location')=='footer'?'selected':'',
			get_option('snoobianalyticsplugin_location')=='header'?'selected':''
        );
}

function snoobianalyticsplugin_cookies_callback() {
    printf(
        '<select id="snoobianalyticsplugin_cookies" name="snoobianalyticsplugin_cookies">
			<option %s>on
			<option %s>off
		</select>
		',
		get_option('snoobianalyticsplugin_cookies')=='on'?'selected':'',
		get_option('snoobianalyticsplugin_cookies')=='off'?'selected':''
    );
}

function snoobianalyticsplugin_options_page() {
	?><!-- fp -->
        <div class="wrap">
			<div style='float: right;'>
				If you don't have Snoobi Analytics<br> yet, then request a <a href='http://www.snoobi.nl/pilot-aanvragen/?snbadname=fromplugin'>free trial.</a>
			</div>
            <h2>Snoobi BV Analyics tracking plugin Settings</h2>           
            <form method="post" action="options.php">
            <?php
                settings_fields('snoobi_opts');
                do_settings_sections( 'snoobipluginoptions');
                submit_button(); 
            ?>
            </form><Br><br>
			With this plugin you can easily add your Snoobi Analytics tracking code to your Wordpress site.<br>
			<br>
			<b>
			Snoobi account ID: </b>
			<br >
			This is your id you received from Snoobi, in the form of <i> yoursite_nl</i>
			<br>
			<b>Location of tracking code:</b>
			<br>
			Do you want the tracking code to be placed at the top of the page or at the bottom? <br>
			We recommend the footer as that ensures the complete web page is loaded before the tracking script runs.<br >
			<b>
			Cookies:</b>
			<br >
			You can select to not let Snoobi use any cookies. <br>
			Snoobi will be fully functional, with the exception of detecting repeat visitors. We recommend to keep this <i>
			‘on’</i>
			<br>
			<br>
			Additional information and contact details for Snoobi B.V can <a  href="http://www.snoobi.nl" target="_blank">
			always be found on our website</a>
			<br >
        </div>
		<!-- fp -->
	<?php
}

if(get_option('snoobianalyticsplugin_snoobi_id')) {
	if(get_option('snoobianalyticsplugin_location')=='header') {
		add_action('wp_head', 'snoobianalyticsplugin_footer' );
	} else {
		add_action('wp_footer', 'snoobianalyticsplugin_footer' );
	}
}

if ( is_admin() ) { // admin actions
	add_action('admin_init', 'snoobianalyticsplugin_init' );
	add_action('admin_menu', 'snoobianalyticsplugin_admin_add_page');
}
