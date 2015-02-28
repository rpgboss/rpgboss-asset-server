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
	'packagemanagement/updateimageorder/:packageid/:imageorder' => 'packagemanagement/update_image_order',
	'packagemanagement/edit/:packageid' => 'packagemanagement/update_package',
	'packagemanagement/submit' => 'packagemanagement/submit_package',
	'packagemanagement/:packageid/requestapproval' => 'packagemanagement/request_approval',
	'packagemanagement/:packageid/delete' => 'packagemanagement/delete_package',
	'packagemanagement/:packageid' => 'packagemanagement/edit_package',
	'packagemanagement' => 'packagemanagement/create_package',

	'projectmanagement/create' => 'projectmanagement/project_create',
	'projectmanagement/:projectid/delete' => 'projectmanagement/delete_project',
	'projectmanagement/removeimage/:imagefile/:projectid' => 'projectmanagement/remove_image',
	'projectmanagement/:projectid/update' => 'projectmanagement/project_update',
	'projectmanagement/updateimageorder/:projectid/:imageorder' => 'projectmanagement/update_image_order',
	'projectmanagement/:projectid' => 'projectmanagement/project_edit',
	'projectmanagement' => 'projectmanagement/projects',

	'adminpanel/unapproved/lookat/:packageid/approve' => 'adminpanel/unapproved_approve_package',
	'adminpanel/unapproved/lookat/:packageid/reject' => 'adminpanel/unapproved_reject_package',
	'adminpanel/unapproved/lookat/:packageid' => 'adminpanel/unapproved_view_package',
	'adminpanel/unapproved' => 'adminpanel/unapproved',

	'project/category/:catslug' => 'projectview/view_category',
	'project/:projectid/:projectname' => 'projectview/view',

	'comment/add/:packageid' => 'comment/add',
	'comment/remove/:packageid' => 'comment/remove',

	'c/:catslug/:packageid/:packageslug' => 'frontend/view_package',
	'c/:catslug' => 'frontend/view_category',

	'user/:userid' => 'frontend/show_user',

	'api/v1/login/with/redirect/:username/:password' => 'apiv1/login_with_redirect',
	'api/v1/login/:username/:password' => 'apiv1/login',
	'api/v1/checkpackagedownload/:id' => 'apiv1/checkpackagedownload',
	'api/v1/downloadpackage/:id' => 'apiv1/downloadpackage',

);