<?php
/* 
Plugin Name: AsrVerifyCode
Version: 0.2.2
Plugin URI: http://www.lostinbeijing.com/wp-plugins/asr-verify-code/
Author: Asr
Author URI: http://www.lostinbeijing.com
Description: Show verify code in the comment form or login form.
*/ 


@session_start();
//add_action('wp_head','add_prototype');
add_action('comment_form','show_asrvcode');
add_action('login_form','showinlogin_asrvcode');
add_action('login_head','add_asrvcodecss');
add_action('wp_authenticate','checklogin_asrvcode');
add_filter('preprocess_comment','check_asrvcode');
add_filter('comment_save_pre','check_asrvcode_2');
add_action('admin_menu', 'add_asr_verify_code_settings');
add_action('admin_notices', 'asr_verify_code_admin_notice');

function add_asr_verify_code_settings()
{
	
	add_options_page("Asr Verify Code","Asr Verify Code",'manage_options','asr_verify_code','asr_verify_code_settings');
}


if (!defined('WP_INCLUDE_URL')) {
	define( 'WP_INCLUDE_URL', get_option('siteurl').'/wp-includes');
}

if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
if (!defined('ASRVERIFYCODE_URL')) {
	define('ASRVERIFYCODE_URL', WP_PLUGIN_URL.'/asr-verify-code');
}

$_SESSION['asrverifycode_length'] = get_option("asrverifycode_length");
function add_prototype()
{
	echo "<script type=text/javascript src='".ASRVERIFYCODE_URL."/js/prototype.js'></script>";
}

function add_asrvcodecss()
{
	
	echo "<link rel='stylesheet' id='asrvcode-css'  href='".ASRVERIFYCODE_URL."/asrverifycode.css' type='text/css' media='all' />";
}

function checklogin_asrvcode()
{
	if(get_option('asrverifycode_wplogin'))
	{
	if(!empty($_POST['log']))
	{
		if ($_SESSION['asr_verifycode'] !== $_POST['asrvcode']) 
		{
			die("Verify Code is error <br>\n<a href=\"javascript:history.back(-1);\">Back</a>");
		}
	}
 }
}	
	

function showinlogin_asrvcode()
{
	if(get_option('asrverifycode_wplogin'))
	{
		
	  echo "\n<p>\n";
	  echo "<label>Verify Code<br />\n";
	  echo "<input type=\"text\" name=\"asrvcode\" id=\"asrvcode\" class=\"input\" size=\"20\" tabindex=\"30\" />";
	  echo "&nbsp;<img alt=\"If you cannot see the CheckCode image,please refresh the page again!\" src=\"".ASRVERIFYCODE_URL."/vcimage.php\" /></label>";
	  echo "</p>\n";
	}
	
}
function show_asrvcode(){
	
	if(get_option('asrverifycode_comment'))
	{
		add_prototype();
	  
	  echo "\n<script type=text/javascript>\n";
	  echo  "var vcmsg = '<span>Verify Code:</span>&nbsp;&nbsp';";
	  
    
    echo "\nvcmsg += '<input type=\"text\" name=\"asrvcode\" maxlength=\"10\" style=\"width:60px;height:10px;\" title=\"Type here what you see in the left image\" />';";
	  echo "vcmsg += '<img alt=\"If you cannot see the VerifyCode image,please refresh the page again!\" src=\"".ASRVERIFYCODE_URL."/vcimage.php\" />';";
	  echo "new Insertion.After($('submit'),vcmsg);";
	  
	  echo "</script>";
	}
	

}

function check_asrvcode($commentdata){
	
	if(get_option('asrverifycode_comment'))
	{

	if ($_SESSION['asr_verifycode'] !== $_POST['asrvcode']) {
		
		die(__('
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Error</title></head>
<body>
<h1 style="color:red">Error:</h1>Sorry!Your Verify Code is wrong.Please go back and try again!<br /><br />

<a href="javascript:history.back(-1);">Back</a>

</body></html>'
));
	}
}
    return $commentdata;

}

function check_asrvcode_2($commentdata)
{

	if ($_SESSION['asr_verifycode'] !== $_POST['asrvcode']) {
		
		die(__('
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Error</title></head>
<body>
<h1 style="color:red">Error:</h1>Don\'t use the Comment Robot. Thank you!<br /><br />

<a href="javascript:history.back(-1);">Back</a>

</body></html>'
));
	}
	
    return $commentdata;

}
function asr_verify_code_settings()
{
?>	<div class="wrap">
<h2>Asr Verify Code Configuration</h2>

<form method="post" action="options.php">
    
    <?php wp_nonce_field('update-options'); ?>
    <input type="hidden" name="asrverifycode_configured" value="1" >
    <table class="form-table">
        <tr valign="top">
        <?php $s = (get_option('asrverifycode_comment'))? "checked" : "" ;  ?>
        <td><input type="checkbox" name="asrverifycode_comment" id="asrverifycode_comment" value="1" <?php print $s;  ?> /> Use Asr Verify Code in submitting comment</td>
        
        </tr>
         
        <tr valign="top">
        <?php $s = (get_option('asrverifycode_wplogin'))? "checked" : "" ; ?>
        <td><input type="checkbox" name="asrverifycode_wplogin" id="asrverifycode_wplogin" value="1" <?php print $s;  ?> /> Use Asr Verify Code in WP Login</td>
        
        </tr>
        
        <tr valign="top">
        <?php $s = get_option('asrverifycode_length') ; ?>
        <td>Set the length of the verify code :<input type="text" name="asrverifycode_length" id=="asrverifycode_length" value="<?php print $s;  ?>"  /> </td>
        
        </tr>
    </table>
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="asrverifycode_configured,asrverifycode_comment,asrverifycode_wplogin,asrverifycode_length" />

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } 

function asr_verify_code_admin_notice()
{
	
	if(!get_option('asrverifycode_configured'))
	{
		echo '<div class="error"><p><strong>' . sprintf( __('Asr Verify Code is disabled. Please go to the <a href="%s">plugin admin page</a> to enable it.', 'asr_verify_code' ), admin_url( 'options-general.php?page=asr_verify_code' ) ) . '</strong></p></div>';
	}
	
}

?>
