<?php
/**
 *
 * MyBB: Topaz - Admin CP
 *
 * Filename: style.php
 *
 * Style Author: Vintagedaddyo
 *
 * V Site: http://community.mybb.com/user-6029.html
 *
 * MyBB Version: 1.8.x
 *
 * Style Version: 1.1
 * 
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

class Page extends DefaultPage
{
	function _generate_breadcrumb()
	{
		if(!is_array($this->_breadcrumb_trail))
		{
			return false;
		}
		$trail = "";
		foreach($this->_breadcrumb_trail as $key => $crumb)
		{
			if($this->_breadcrumb_trail[$key+1])
			{
				$trail .= "<a href=\"".$crumb['url']."\">".$crumb['name']."</a>";
				if($this->_breadcrumb_trail[$key+2])
				{
					$trail .= " &raquo; ";
				}
			}
			else
			{
				$trail .= " &raquo; <span class=\"active\">".$crumb['name']."</span>";
			}
		}
		return $trail;
	}

	/**
	 * Output the page header.
	 *
	 */
	
	function output_header($title="")
	{
		global $mybb, $admin_session, $lang, $plugins;

		$args = array(
			'this' => &$this,
			'title' => &$title,
		);

		$plugins->run_hooks("admin_page_output_header", $args);

		if(!$title)
		{
			$title = $lang->mybb_admin_panel;
		}

		$rtl = "";
		if($lang->settings['rtl'] == 1)
		{
			$rtl = " dir=\"rtl\"";
		}

		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\"{$rtl}>\n";
		echo "<head profile=\"http://gmpg.org/xfn/1\">\n";
		echo "	<title>".$title."</title>\n";
        echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";		
		echo "	<meta name=\"author\" content=\"MyBB Group\" />\n";
		echo "	<meta name=\"copyright\" content=\"Copyright ".COPY_YEAR." MyBB Group.\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"styles/".$this->style."/main.css?ver=1813\" type=\"text/css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"styles/".$this->style."/modal.css?ver=1813\" type=\"text/css\" />\n";

		// Load stylesheet for this module if it has one
		if(file_exists(MYBB_ADMIN_DIR."styles/{$this->style}/{$this->active_module}.css"))
		{
			echo "	<link rel=\"stylesheet\" href=\"styles/{$this->style}/{$this->active_module}.css\" type=\"text/css\" />\n";
		}

		echo "	<script type=\"text/javascript\" src=\"../jscripts/jquery.js?ver=1823\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"../jscripts/jquery.plugins.min.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"../jscripts/general.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"./jscripts/admincp.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"./jscripts/tabs.js\"></script>\n";

		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.min.css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.structure.min.css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.theme.min.css\" />\n";
		echo "	<script src=\"jscripts/jqueryui/js/jquery-ui.min.js?ver=1813\"></script>\n";

		// Stop JS elements showing while page is loading (JS supported browsers only)
		echo "  <style type=\"text/css\">.popup_button { display: none; } </style>\n";
		echo "  <script type=\"text/javascript\">\n".
				"//<![CDATA[\n".
				"	document.write('<style type=\"text/css\">.popup_button { display: inline; } .popup_menu { display: none; }<\/style>');\n".
                "//]]>\n".
                "</script>\n";

		echo "	<script type=\"text/javascript\">
//<![CDATA[
var loading_text = '{$lang->loading_text}';
var cookieDomain = '{$mybb->settings['cookiedomain']}';
var cookiePath = '{$mybb->settings['cookiepath']}';
var cookiePrefix = '{$mybb->settings['cookieprefix']}';
var cookieSecureFlag = '{$mybb->settings['cookiesecureflag']}';
var imagepath = '../images';

lang.unknown_error = \"{$lang->unknown_error}\";
lang.saved = \"{$lang->saved}\";
//]]>
</script>\n";
		echo $this->extra_header;
		echo "</head>\n";
		echo "<body>\n";
		echo "<div id=\"container\">\n";
		echo "	<div id=\"logo\"><h1><span class=\"invisible\">{$lang->mybb_admin_cp}</span></h1></div>\n";
		$username = htmlspecialchars_uni($mybb->user['username']);
		echo "	<div id=\"welcome\"><span class=\"logged_in_as\">{$lang->logged_in_as} <a href=\"index.php?module=user-users&amp;action=edit&amp;uid={$mybb->user['uid']}\" class=\"username\">{$username}</a></span> | <a href=\"{$mybb->settings['bburl']}\" target=\"_blank\" class=\"forum\">{$lang->view_board}</a> | <a href=\"index.php?action=logout&amp;my_post_key={$mybb->post_code}\" class=\"logout\">{$lang->logout}</a></div>\n";
		echo $this->_build_menu();
		echo "	<div id=\"page\">\n";
		echo "		<div id=\"left_menu\">\n";
		echo $this->submenu;
		echo $this->sidebar;
		echo "		</div>\n";
		echo "		<div id=\"content\">\n";
		echo "			<div class=\"breadcrumb\">\n";
		echo $this->_generate_breadcrumb();
		echo "			</div>\n";
		echo "           <div id=\"inner\">\n";
		if(isset($admin_session['data']['flash_message']) && $admin_session['data']['flash_message'])
		{
			$message = $admin_session['data']['flash_message']['message'];
			$type = $admin_session['data']['flash_message']['type'];
			echo "<div id=\"flash_message\" class=\"{$type}\">\n";
			echo "{$message}\n";
			echo "</div>\n";
			update_admin_session('flash_message', '');
		}

		if(!empty($this->extra_messages) && is_array($this->extra_messages))
		{
			foreach($this->extra_messages as $message)
			{
				switch($message['type'])
				{
					case 'success':
					case 'error':
						echo "<div id=\"flash_message\" class=\"{$message['type']}\">\n";
						echo "{$message['message']}\n";
						echo "</div>\n";
						break;
					default:
						$this->output_error($message['message']);
						break;
				}
			}
		}

		if($this->show_post_verify_error == true)
		{
			$this->output_error($lang->invalid_post_verify_key);
		}
	}

	/**
	 * Output the page footer.
	 */
	
	function output_footer($quit=true)
	{
		global $mybb, $maintimer, $db, $lang, $plugins;

		$args = array(
			'this' => &$this,
			'quit' => &$quit,
		);

		$plugins->run_hooks("admin_page_output_footer", $args);

		$memory_usage = get_friendly_size(get_memory_usage());

		$totaltime = format_time_duration($maintimer->stop());
		$querycount = $db->query_count;

		if(my_strpos(getenv("REQUEST_URI"), "?"))
		{
			$debuglink = htmlspecialchars_uni(getenv("REQUEST_URI")) . "&amp;debug=1#footer";
		}
		else
		{
			$debuglink = htmlspecialchars_uni(getenv("REQUEST_URI")) . "?debug=1#footer";
		}

		echo "			</div>\n";
		echo "		</div>\n";
		echo "	<br style=\"clear: both;\" />";
		echo "	<br style=\"clear: both;\" />";
		echo "	</div>\n";
		//echo "<div id=\"footer\"><p class=\"generation\">".$lang->sprintf($lang->generated_in, $totaltime, $debuglink, $querycount, $memory_usage)."</p><p class=\"powered\">Powered By <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB</a>, &copy; 2002-".COPY_YEAR." <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB Group</a>.</p></div>\n";
		echo "<div id=\"footer\"><p class=\"generation\">".$lang->sprintf($lang->generated_in, $totaltime, $debuglink, $querycount, $memory_usage)."</p><p class=\"powered\">Powered By <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB</a>, &copy; 2002-".COPY_YEAR." <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB Group</a> All Rights Reserved.&nbsp;&nbsp;Theme \"Topaz ACP\" created by <a href=\"https://github.com/vintagedaddyo/MyBB_ACP_Style_Topaz\" target=\"_blank\"><b>Vintagedaddyo</b></a>.</p></div>\n";
		if($mybb->debug_mode)
		{
			echo $db->explain;
		}
		echo "</div>\n";
		echo "</body>\n";
		echo "</html>\n";

		if($quit != false)
		{
			exit;
		}
	}
}

		
		
	

class SidebarItem extends DefaultSidebarItem
{
}

class PopupMenu extends DefaultPopupMenu
{
}

class Table extends DefaultTable
{
}

class Form extends DefaultForm
{
}

class FormContainer extends DefaultFormContainer
{
}