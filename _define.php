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

$this->registerModule(
		/* Name */
		"dcReCaptcha2",
		/* Description*/
		"reCaptcha spam filter based on dcReCaptcha by Benoit de Marne",
		/* Author */
		"Etienne Bagnoud",
		/* Version */
		'0.0.1',
		/* Properties */
		array(
				'permissions' => 'usage,contentadmin',
				'type' => 'plugin',
				'priority' => 200,
				'dc_min' => '2.5.3',
				'support' => '',
				'details' => ''				
		)
);
