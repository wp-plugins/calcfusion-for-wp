<?php
/*
Plugin Name: CalcFusion for WP 
Plugin URI: http://wordpress.org/plugins/calcfusion-for-wp/
Description: This plugin makes it simple to add CalcFusion API to your WordPress
Version: 1.1.2
Author: CalcFusion
Author URI: http://calcfusion.com
Text Domain: 
Domain Path: /languages/
License: GPLv2

CalcFusion for WP
Copyright (C) 2014, CalcFusion - support@calcfusion.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define( "CALCFUSION_CONTEXT_NAME", 'Wordpress' );

define( "CALCFUSIONWP_VERSION", '1.1.2' );

define( "CALCFUSIONWP", trailingslashit( plugin_dir_url( __FILE__ ) ) );

define( "CALCFUSIONWP_PATH", plugin_dir_path( __FILE__ ) );

function calcfusion_initialize_plugin() 
{
	require_once CALCFUSIONWP_PATH."/autoload.php";
	
	$options = get_option('calcfusion-wp-options');
	if($options != null)
	{
		define( "CALCFUSIONWP_ACCTID", absint($options['calcfusion_property_acctid']));
		define( "CALCFUSIONWP_USERNAME", esc_attr($options['calcfusion_property_username']));
		define( "CALCFUSIONWP_PASSWORD", esc_attr($options['calcfusion_property_password']));
		define( "CALCFUSIONWP_APPKEY", esc_attr($options['calcfusion_property_appkey']));
		define( "CALCFUSIONWP_API_URL", esc_url($options['calcfusion_property_api_url']));
	}
}

// Global CalcFusion activation hook
function calcfusion_handle_activation() {
	// add options on DB
	if(checkVersionRequired())
	{
		global $wp_calcfusion_client;
		global $wp_clientTokenInfo;
	}
}

// Global CalcFusion deactivation hook
function calcfusion_handle_deactivation() {
	// delete options on DB
	remove_action('admin_notices', 'checkVersionRequired' ) ;
	delete_option('calcfusion_wp_admin_notices');
	delete_option('calcfusion-wp-options');
}

// Activation hook for some basic initialization
register_activation_hook( __FILE__,  'calcfusion_handle_activation' );
register_deactivation_hook( __FILE__, 'calcfusion_handle_deactivation' );

// Main CalcFusion activation hook
if (is_admin()) {
	$my_settings_page = new CalcFusionSettingsPage();
}

add_action( 'plugins_loaded', 'calcfusion_initialize_plugin' );
add_action( 'admin_notices', 'getAdminNotices', 0 ) ;
function getAdminNotices()
{
	if ($notices = get_option('calcfusion_wp_admin_notices')) {
		if (sizeof($notices) > 0) {
			echo $notices[0];
			
			// to display admin message once
			delete_option('calcfusion_wp_admin_notices');
		}
	}
}

// check the minimum required version and server settings
function checkVersionRequired()
{
	$hasError = false;
	$minimumVersion = "5.3";
	$minimumAsyncVersion = "5.5";
	
	$version = explode('.', phpversion());
	$major = (int)$version[0];
	$minor = (int)$version[1];
	$major_minor = $major.".".$minor;

	$curlSettings = get_curlSettings();
	$asyncDNS = get_asyncDNS();
	
	$message = 'PHP version (<b>'.$major_minor.'</b>)';
	$message .= '<br>CURL Support: <b>'.get_curlSettings().'</b>';
	$message .= '<br>AsyncDNS enabled: <b>'.get_asyncDNS().'</b>';
	
	if (strnatcmp($major_minor, $minimumAsyncVersion) >= 0)
	{
		if($curlSettings != "enabled")
		{
			$hasError = true;
			$message .= '<br>CURL support must be set to enabled.';
		}
		
		if($asyncDNS == 'No')
		{
			$message .= '<br>Only synchronous service calls can be used with the current server settings.';
			$message .= '<br>To enable asynchronous service calls, the CURL AsynchDNS setting must be set to Yes.';
		}
	}
	else
	{
		$hasError = true;
		$message .= 'PHP version ('.$major_minor.') is below the minimum required version, the plugin only works for '.$minimumVersion.' or higher.';
		
		if($curlSettings != "enabled")
			$message .= '<br>CURL support must set to enabled.';
		
		if (strnatcmp($major_minor, $minimumVersion) >= 0)
			$message .= '<br>Please contact us and we can provide you with a solution for PHP ('.$major_minor.')';
	}
	
	if($hasError)
		$html = '<div class="error">';
	else
		$html = '<div class="updated">';
	
	$html .= '<p>';
	$html .= __( $message, 'calcfusion-wp' );
	$html .= '</p>';
	$html .= '</div><!-- /.updated -->';
	
	if($hasError)
	{
		unset( $_GET['activate'] );
		$name = get_file_data( __FILE__, array ( 'Plugin Name' ), 'plugin' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	
	$notices = get_option('calcfusion_wp_admin_notices', array());
	$notices[]= $html;
	update_option('calcfusion_wp_admin_notices', $notices);
	
	return !$hasError;
}

// get CURL AsynchDNS value settings
function get_asyncDNS()
{
	ob_start();
	phpinfo();
	$phpinfo = array('phpinfo' => array());
	if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		foreach($matches as $match)
			if(strlen($match[1]))
				$phpinfo[$match[1]] = array();
			elseif(isset($match[3]))
			$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			else
				$phpinfo[end(array_keys($phpinfo))][] = $match[2];

			return $phpinfo['curl']['AsynchDNS'];
} 

function get_curlSettings()
{
	ob_start();
	phpinfo();
	$phpinfo = array('phpinfo' => array());
	if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		foreach($matches as $match)
			if(strlen($match[1]))
				$phpinfo[$match[1]] = array();
			elseif(isset($match[3]))
			$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			else
				$phpinfo[end(array_keys($phpinfo))][] = $match[2];

			return $phpinfo['curl']['cURL support'];
}

add_action( 'wp_ajax_calcfusion_request_service', 'calcfusion_request_service');
add_action( 'wp_ajax_nopriv_calcfusion_request_service', 'calcfusion_request_service');
function calcfusion_request_service() {
	$servicePath = sanitize_text_field($_POST["servicePath"]);
	$method = sanitize_text_field($_POST["method"]);
	$parameters = $_POST["parameters"];

	$cf_client = new CalcFusionClient(CALCFUSIONWP_API_URL);
	$output = $cf_client->login(CALCFUSIONWP_USERNAME, CALCFUSIONWP_PASSWORD, CALCFUSIONWP_ACCTID, CALCFUSIONWP_APPKEY);

	if($output)
	{
		$params =array();
		$params["cfxlFrom"]="CFXL";
		foreach($parameters as $key => $value)
		{
			$value = str_replace("\\\"", "\"", $value);
			$params[$key] = htmlentities ( trim ( $value ) , ENT_NOQUOTES );
		}
		
		$result = $cf_client->requestService($servicePath, $method, $params);
		echo $result;
	}

	die();
}

class CalcFusionSettingsPage
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	private $userId;
	public $cf_client;
	
	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		
		// register wp ajax functions 
		add_action( 'wp_ajax_calcfusion_action_login', array( $this,'calcfusion_action_login'));
		add_action( 'wp_ajax_calcfusion_action_computation_list', array( $this,'calcfusion_action_computation_list'));
		add_action( 'wp_ajax_calcfusion_action_download', array( $this,'calcfusion_action_download'));
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('json2');
		wp_enqueue_script( 'cf_sha1_script', plugin_dir_url( __FILE__ ) . '/js/jquery.sha1.js' );
	}
	
	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page('CalcFusion','CalcFusion','manage_options','calcfusion-for-wp',array( $this, 'create_admin_page' ));
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'calcfusion-wp-options' );
		?>
		<div class="wrap">
			<h2></h2>
		</div>
        <div class="wrap">
            <div>
				<div style="display: inline-block;width: 204px;"><img alt="" src="<?php echo plugin_dir_url( __FILE__ ).'images/CalcFusion_logo.png'?>" style="display: inline;"></div>
				<div style="display: inline-block;vertical-align: top;padding-top: 18px;"><h2>Setup</h2></div>
			</div>
			<h3>CalcFusion for WordPress plugin version <?php echo CALCFUSIONWP_VERSION?></h3>
			
			<p>These parameters are available from the Accounts page of the <a href="https://console.calcfusion.com" target="_blank">CalcFusion Console</a>.
			<br>Sign-in to your account to check. If you do not have an account yet, register for a <a href="http://calcfusion.com/trial-registration" target="_blank">Free Trial</a> now or 
			<a href="http://calcfusion.com/contact-us" target="_blank">Contact Us</a>. 
			</p>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'calcfusion-wp-option-group' );   
                do_settings_sections( 'calcfusion-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
		<div class="wrap">
			<input type="button" class="button-primary" value="Login" style="width:104px; display: inline;" onclick="doTestLogin()"/>
			<p class="description" style="display: inline;">Check if your Account Parameters are correct. Also to enable the Computation Management below.</p>
			<h4><div id="login-test-result"/></h4>
		</div>
		
		<hr/>
		<div class="wrap">
			<h3>Computation Management</h3>
			<div>
				<div style="display:inline-block;font-weight: bold;">Computation: </div>
				<select id="computationList" style="width: 200px;" onchange="enableUploadButton()" disabled >
					<option value="0">-- Select Computation --</option>
				</select>
				<div style="height:10px;"></div>
				<div style="font-weight: bold;">Upload</div>
				<form id="fileUploadForm" target="uploadFrame" action="https://console.calcfusion.com/calcfusion/pages/clientfileupload.jsp" method="post" enctype="multipart/form-data">
				    <input type="hidden" name="return" value="<?php echo plugin_dir_url( __FILE__ ).'uploadcomplete.php'?>"/>
				    <input type="hidden" name="active" value="1"/>
				    <input type="hidden" name="acctid" value="<?php echo isset( $this->options['calcfusion_property_acctid'] ) ? absint( $this->options['calcfusion_property_acctid']) : '0'; ?>"/>
				    <input type="hidden" id="userId" name="userid" value="0"/>
				    <input type="hidden" id="computeId" name="computeid" value="0"/>
				    <p style="font-size: 12px;display: inline;">Upload Spreadsheet (.xlsx)</p>
				    <input id="uploadFile" type="file" name="uploadFile" accept=".xlsx" style="border: 1px solid #c4c4c4;" onchange="enableUploadButton()" disabled/>
				    <div style="display: inline-block;vertical-align: bottom;">
				   		<input id="uploadBtn" type="submit" value="Upload" name="submit" style="vertical-align: middle;" disabled>
				   	</div>
				   	<div id="fileuploadAuditResult" style="display: inline;padding-left: 10px;font-size: 12px;"></div>
				   	<iframe name="uploadFrame" src="" frameborder="0" scrolling="no" height="0px" width="0px"></iframe>
				</form>
				
				<div style="height:10px;"></div>
				<div style="font-weight: bold;">Download</div>
				<div style="text-align: left;margin: 0px;vertical-align: bottom;padding: 0px;" disabled>
					<a href="javascript:onFileDownload();" disabled><img alt="" src="<?php echo plugin_dir_url( __FILE__ ).'images/ico24_download.png'?>"/></a>
					<span> Excel File</span>
				 </div>
			 </div>
		</div>
		
		<script type="text/javascript">
		var $ = jQuery.noConflict();
		var userId = 0;
		function doTestLogin()
		{
			var date = new Date();
			var testTime = date.getMilliseconds();
			var ajaxdata = createAjaxSecParam('calcfusion_action_login');
			$.post(ajaxurl, ajaxdata, function(data) {
				if(data != null)
				{
					var response = data.response;
					if(response != null)
					{
						if(response.status == "OK")
						{
							$("#login-test-result").empty().append("Login successful " + testTime/1000 + " seconds");
							$("#computationList").removeAttr("disabled");
							$("#uploadFile").removeAttr("disabled");
							userId = data.data.clientApp.user.userId;
							$("#userId").val(userId);
							
							getComputationList();
						}
						else
							$("#login-test-result").empty().append(response.errorMessage);
					}
				}
				
			}, "json")
			.fail(function(data) {
				alert("error" + data);
			}, "json");
		}

		function getComputationList()
		{
			var ajaxdata = createAjaxSecParam('calcfusion_action_computation_list');
			$.post(ajaxurl, ajaxdata, function(data) {
				if(data != null)
				{
					$("#computationList").empty().append("<option value=\"0\">-- Select Computation --</option>");
					$.each( data.resultList, function( index, dataObj ) {
						var folderOpt = "<option value=\""+ dataObj.bob_id +"\">"+ dataObj.bob_id + " - " + encodeStringValue(dataObj.bob_label) +"</option>";
						$("#computationList").append(folderOpt);
					});
				}
			}, "json")
			.fail(function(data) {
				alert("Request Failed!");
			}, "json");
		}

		function createAjaxSecParam(action)
		{
			var password = $('#calcfusion_property_password').val();
			if(!is_sha1(password))
				password = $.sha1(password);
				
			var ajaxdata = {'action': action,
					'accountId': $('#calcfusion_property_acctid').val(),
					'username' : $('#calcfusion_property_username').val(),
					'password' : password,
					'appkey': $('#calcfusion_property_appkey').val(),
					'apiURL': $('#calcfusion_property_api_url').val()};
			
			return ajaxdata;
		}
		
		function encodeStringValue(value)
		{
			value = replaceAll("&", "&amp;", value);
			value = replaceAll("<", "&lt;", value);
			value = replaceAll(">", "&gt;", value);
			value = replaceAll('"', "&quot;", value);
			value = replaceAll("'", "&apos;", value);
			
			return value;
		}

		function replaceAll(find, replace, str) {
			  return str.replace(new RegExp(find, 'g'), replace);
		}

		function is_sha1(str)
		{
			return /\b[0-9a-f]{40}\b/.test(str);
		}
		
		function enableUploadButton()
		{
			$("#computeId").val($("#computationList").val());
			if($("#computationList").val() != 0 && $("#uploadFile").val() != "")
				$("#uploadBtn").removeAttr("disabled");
			else
				$("#uploadBtn").attr("disabled", "disabled");
		} 

		function onFileUploadComplete(computeId, version, result)
		{
			if(result == "OK")
				$("#fileuploadAuditResult").empty().append("File upload successful!");
			else
				$("#fileuploadAuditResult").empty().append(result);
		}

		function onFileDownload(){
			if($('#computationList').val() > 0)
			{
				var ajaxdata = createAjaxSecParam('calcfusion_action_download');
				ajaxdata.computeId = $('#computationList').val();
				ajaxdata.userId = userId;
				$.post(ajaxurl, ajaxdata, function(data) {
					$("body").append(data);
				})
				.fail(function(data) {
					alert("Request Failed!");
				});
			}
			else
				alert("Please select a computation to download.");
		}
		
		</script>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting('calcfusion-wp-option-group', 'calcfusion-wp-options', array( $this, 'sanitize' ));

        add_settings_section('setting_section_id', '', array( $this, 'print_section_info' ), 'calcfusion-setting-admin');  

        add_settings_field(
            'calcfusion_property_acctid', 
            'Account Id:', // Title 
            array( $this, 'accoutId_callback' ), // Callback
            'calcfusion-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'calcfusion_property_username', 
            'User Name:', 
            array( $this, 'username_callback' ), 
            'calcfusion-setting-admin', 
            'setting_section_id'
        );  

        add_settings_field(
	        'calcfusion_property_password',
	        'Password:',
	        array( $this, 'password_callback' ),
	        'calcfusion-setting-admin',
	        'setting_section_id'
        );
        
        add_settings_field(
	        'calcfusion_property_appkey',
	        'App Key:',
	        array( $this, 'appkey_callback' ),
	        'calcfusion-setting-admin',
	        'setting_section_id'
        );
        
        add_settings_field(
	        'calcfusion_property_api_url',
	        'CalcFusion End Point:',
	        array( $this, 'apiurl_callback' ),
	        'calcfusion-setting-admin',
	        'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['calcfusion_property_acctid'] ) )
            $new_input['calcfusion_property_acctid'] = absint( $input['calcfusion_property_acctid'] );

        if( isset( $input['calcfusion_property_username'] ) )
            $new_input['calcfusion_property_username'] = sanitize_email( $input['calcfusion_property_username'] );
        
        if( isset( $input['calcfusion_property_password'] ) )
        {
        	if($this->is_sha1($input['calcfusion_property_password']))
        		$new_input['calcfusion_property_password'] = sanitize_text_field( $input['calcfusion_property_password']);
        	else 
        		$new_input['calcfusion_property_password'] = sha1(sanitize_text_field( $input['calcfusion_property_password']));
        }
        
        if( isset( $input['calcfusion_property_appkey'] ) )
        	$new_input['calcfusion_property_appkey'] = sanitize_text_field( $input['calcfusion_property_appkey'] );
        
        if( isset( $input['calcfusion_property_api_url'] ) )
        	$new_input['calcfusion_property_api_url'] = sanitize_text_field( $input['calcfusion_property_api_url'] );

        return $new_input;
    }

    /**
     * Check if string is already in SHA1
     */
    public function is_sha1( $str ) {
		return ( bool ) preg_match( '/^[0-9a-f]{40}$/i', $str );
	}
    
    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the Account Id settings option and print its values
     */
    public function accoutId_callback()
    {
        printf(
            '<input type="text" id="calcfusion_property_acctid" style="width: 100px;" name="calcfusion-wp-options[calcfusion_property_acctid]" value="%s" maxlength="8"/>',
            isset( $this->options['calcfusion_property_acctid'] ) ? absint( $this->options['calcfusion_property_acctid']) : ''
        );
        printf('<p class="description">Enter your numerical CalcFusion Account ID</p>');
    }

    /** 
     * Get the User Name settings option and print its values
     */
    public function username_callback()
    {
        printf(
            '<input type="text" id="calcfusion_property_username" style="width: 400px;" name="calcfusion-wp-options[calcfusion_property_username]" value="%s" maxlength="50"/>',
            isset( $this->options['calcfusion_property_username'] ) ? esc_attr( $this->options['calcfusion_property_username']) : ''
        );
        printf('<p class="description">Enter the username (registered email address) of the CalcFusion user assigned with Execution Rights.</p>');
    }
    
    /**
     * Get the Password settings option and print its values
     */
    public function password_callback()
    {
    	printf(
    		'<input type="password" id="calcfusion_property_password" style="width: 400px;"  name="calcfusion-wp-options[calcfusion_property_password]" value="%s" maxlength="50"/>',
    		isset( $this->options['calcfusion_property_password'] ) ? 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' : ''
    	);
    	printf('<p class="description">Enter your password</p>');
    }
    
    /**
     * Get the App Key settings option and print its values
     */
    public function appkey_callback()
    {
    	printf(
	    	'<input type="password" id="calcfusion_property_appkey" style="width: 200px;"  name="calcfusion-wp-options[calcfusion_property_appkey]" value="%s" maxlength="30"/>',
	    	isset( $this->options['calcfusion_property_appkey'] ) ? 'xxxxxxxxxxxxxxxx' : ''
    	);
    }
    
    /**
     * Get the API URL settings option and print its values
     */
    public function apiurl_callback()
    {
    	printf(
	    	'<input type="text" id="calcfusion_property_api_url" style="width: 400px;" name="calcfusion-wp-options[calcfusion_property_api_url]" value="%s" maxlength="100"/>',
	    	isset( $this->options['calcfusion_property_api_url'] ) ? esc_url( $this->options['calcfusion_property_api_url']) : 'https://api.calcfusion.com/calcfusion/rest'
    	);
    }
    
    private function login_cf_client()
    {
    	$accountId = absint($_POST["accountId"]);
    	$username = sanitize_email($_POST["username"]);
    	
    	$password = sanitize_text_field($_POST["password"]);
    	if($password != "" && $password != sha1('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'))
    	{
    		if(!$this->is_sha1($password))
    			$password = sha1($password);
    	}
    	else
    		$password = CALCFUSIONWP_PASSWORD;
    	
    	$appkey = sanitize_text_field($_POST["appkey"]);
    	if($appkey == "" || $appkey == 'xxxxxxxxxxxxxxxx')
    		$appkey = CALCFUSIONWP_APPKEY;
    	
    	$apiURL = sanitize_text_field($_POST["apiURL"]);
    	
    	$this->cf_client = new CalcFusionClient($apiURL);
    	return $this->cf_client->login($username, $password, $accountId, $appkey);
    }
    
    /**
     * WP Ajax Calcfusion login callback function
     */
    public function calcfusion_action_login() 
    {
    	$output = $this->login_cf_client();
    	if($output)
    	{
    		$responseData= json_decode($output, true);
    		$clientInfoStr = $responseData["data"];
    		$clientTokenInfo = ClientAccessTokenInfo::getObjFromAssArray($clientInfoStr);
    		$this->userId = $clientTokenInfo->getClientApp()->getAuthorizedUser()->getUserId();
    	}
    
    	echo $output;
    	exit;
    }
    
    /**
     * WP Ajax Calcfusion computation list callback function
     */
    public function calcfusion_action_computation_list()
    {
    	$output = $this->login_cf_client();
    	if($output)
    	{
	    	$param = array();
	    	$computationList = $this->cf_client->requestService("computations/folder/list", "GET", $param);
    	}
    	
    	echo $computationList;
    	exit;
    }
    
    /**
     * WP Ajax Calcfusion download callback function
     */
    public function calcfusion_action_download()
    {
    	$accountId = absint($_POST["accountId"]);
    	$apiURL = sanitize_text_field($_POST["apiURL"]);
    	$computeID = absint($_POST["computeId"]);
    	$userId = absint($_POST["userId"]);
    	
    	$result = $this->login_cf_client();
    	if($result)
    	{
    		$param = array();
    		$param["cfxlFrom"]="CFXL";
    		$param["computationID"] = $computeID;
    			
    		$result = $this->cf_client->requestService("computations/file/list", "GET", $param);
    		if($result)
    		{
    			echo $result;
    			$responseData= json_decode($result, true);
    			$resultList = $responseData["resultList"];
    			$fileId = 0;
    			foreach ($resultList as $object) {
    				if($object["cdo_active"] == 1)
    				{
    					$fileId = $object["bob_id"];
    					$filename = $object["att_filename"];
    					break;
    				}
    			}
    
    			if($fileId != 0)
    			{
    				$result = $this->cf_client->requestService("file/request/".$fileId, "GET", $param);
    				if($result)
    				{
    					$resultObj= json_decode($result, true);
    					$resultData = $resultObj["data"];
    					$fileRequestId = $resultData["fileRequestId"];
    
    					$source = "$apiURL/file/download/request/$fileRequestId?userId=$userId&accountId=$accountId";
    					echo "<iframe src='$source'></iframe>";
    				}
    			}
    		}
    	}
    	
    	exit;
    }
}
