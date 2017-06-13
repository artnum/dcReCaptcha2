<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dcReCaptcha, a plugin for Dotclear 2.
#
# Copyright (c) 2013 Benoit de Marne and contributors
# benoit.de.marne@gmail.com
# Many thanks to Association Dotclear
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

require_once dirname(__FILE__).'/inc/class.dc.recaptcha.php';

# check settings before load the filter
$core->blog->settings->addNamespace('dcReCaptcha');
$blog_settings =& $core->blog->settings->dcReCaptcha;

$dcRecaptcha_settings = array(
	'public_key' => $blog_settings->reCaptcha_public_key,
	'private_key' => $blog_settings->reCaptcha_private_key,
	'theme' => $blog_settings->reCaptcha_theme,
	'comments_form_enable' => $blog_settings->reCaptcha_comments_form_enable
);

# reCAPTCHA is not correctly configured
if(empty($dcRecaptcha_settings['private_key'])
|| empty($dcRecaptcha_settings['public_key'])) {
	return;
}

# adding behaviors
$core->addBehavior('publicBeforeCommentCreate', array('dcReCaptchaBehaviorsPublic','publicBeforeDocument'));

if($dcRecaptcha_settings['comments_form_enable']) {
	$core->addBehavior('publicCommentFormAfterContent', array('dcReCaptchaBehaviorsPublic','publicCommentFormAfterContent'));
}

$core->tpl->addValue('dcReCaptchaForm', array('tplDcReCaptcha', 'dcReCaptchaForm'));

class dcReCaptchaBehaviorsPublic
{
	public static function publicBeforeDocument()
	{
		global $_ctx;
      global $core;

		# start session if not already started
		$session_id = session_id();
		if (empty($session_id)) {
			session_start();
		}
		
		$dcReCaptcha = new dcReCaptcha($core);

		# if the commentator is not authenticated
		if (!isset($_SESSION['recaptcha_ok'])) {
			$resp = $dcReCaptcha->checkAnswer();
		   	
			if ($resp === true) {
				$_SESSION['recaptcha_ok'] = true;
			} else {
				$_SESSION['recaptcha_ok'] = false;
				unset($_SESSION['recaptcha_ok']);
			}		
		}
	}

	public static function publicCommentFormAfterContent($core)
	{
		if (!isset($_SESSION['recaptcha_ok'])) {
			$dcReCaptcha = new dcReCaptcha($core);
			echo $dcReCaptcha->getReCaptchaHtml();
		}
	}
}

class tplDcReCaptcha
{
	public static function dcReCaptchaForm($attr, $content)
	{
		global $core;
		$res = '';
		
		if (!isset($_SESSION['recaptcha_ok'])) {
			
			$dcReCaptcha = new dcReCaptcha($core);
			$res = '<p id="dcrecaptcha-form">'.
				$dcReCaptcha->getReCaptchaHtml().
				'</p>';
		}
		return $res;
		
	}
}
