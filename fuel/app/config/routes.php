<?php
return array(
	'_root_'  => 'frontend/index',  // The default route
	'_404_'   => 'frontend/404',    // The main 404 route

	'forgot-password' => 'frontend/lost_password',
	'forgot-password/attempt' => 'frontend/lost_password_attempt',

	'check/email/:name' => 'register/check_email',
	'check/displayname/:name' => 'register/check_displayname',
	'check/username/:name' => 'register/check_username',

	'register' => 'register/register',
	'register/attempt' => 'register/attempt',

	'search' => 'frontend/search',

	'register/confirmation_notice' => 'register/confirmation_notice',

	'login/attempt' => 'login/attempt',
	'login' => 'login/login',
	'logout' => 'login/logout',

	'profile' => 'profile/show',
	'profile/save' => 'profile/save',

	'activate/account/:activationkey' => 'register/activate_account',

	'packagemanagement/removeimage/:imagefile/:packageid' => 'packagemanagement/remove_image',
	'packagemanagement/:updateimageorder/:packageid/:imageorder' => 'packagemanagement/update_image_order',
	'packagemanagement/edit/:packageid' => 'packagemanagement/update_package',
	'packagemanagement/submit' => 'packagemanagement/submit_package',
	'packagemanagement/:packageid/requestapproval' => 'packagemanagement/request_approval',
	'packagemanagement/:packageid/delete' => 'packagemanagement/delete_package',
	'packagemanagement/:packageid' => 'packagemanagement/edit_package',
	'packagemanagement' => 'packagemanagement/create_package',


	'adminpanel/unapproved/lookat/:packageid/approve' => 'adminpanel/unapproved_approve_package',
	'adminpanel/unapproved/lookat/:packageid/reject' => 'adminpanel/unapproved_reject_package',
	'adminpanel/unapproved/lookat/:packageid' => 'adminpanel/unapproved_view_package',
	'adminpanel/unapproved' => 'adminpanel/unapproved',

	'comment/add/:packageid' => 'comment/add',

	'c/:catslug/:packageid/:packageslug' => 'frontend/view_package',
	'c/:catslug' => 'frontend/view_category',

	'user/:userid' => 'frontend/show_user',

	'api/v1/login' => 'apiv1/login',
	'api/v1/logout' => 'apiv1/logout',
	'api/v1/get_user' => 'apiv1/get_user',
	'api/v1/get_user_by_id' => 'apiv1/get_user_by_id',
	'api/v1/get_categories' => 'apiv1/get_categories',
	'api/v1/get_packages_from_category' => 'apiv1/get_packages_from_category',
	'api/v1/get_package' => 'apiv1/get_package',

);