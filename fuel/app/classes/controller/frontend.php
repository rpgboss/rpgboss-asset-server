<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Frontend extends \LayoutController
{

	public function action_index()
	{

		$this->data->userpanel = -1;

		$data = array();

		$result = array();
		$data['categories'] = Model_Category::find('all');
		foreach($data['categories']  as $cat ) {
			$packages = Model_Package::find('all', array(
				'where' => array('verified'=>1, 'category_id'=>$cat->id),
				'limit' => 3,
				'order_by' => array('created_at'=>'DESC')
			));
			$result[$cat->slug] = $packages;
		}
		$data['packages'] = $result;

		$this->data->view = View::forge('frontend/index', $data);
	}

	public function action_search()
	{
		$term = \Fuel\Core\Input::get('term');

		$data = array();
		$data['term'] = $term;

		$packages = Model_Package::find('all', array(
			'where' => array(
				array('name','like','%'.$term.'%'),
				'verified'=>1
			),
			'order_by' => array('created_at' =>'DESC')
		));

		$data['packages'] = $packages;

		$projects = Model_Project::find('all', array(
			'where' => array(
				array('name','like','%'.$term.'%'),
				'verified'=>0
			),
			'order_by' => array('created_at' =>'DESC')
		));

		$data['projects'] = $projects;

		$this->data->view = View::forge('frontend/search',$data);
	}

	public function action_view_package()
	{
		$data = array();
		$data['isAuthed'] = $this->data->isAuthed;
		$data['currentPackage'] = Model_Package::find($this->param('packageid'));

		if($data['currentPackage']==null) {
			$this->data->view = View::forge('frontend/no_package');
		} else {
			if($data['currentPackage']->verified==1) {
				$this->data->view = View::forge('frontend/view_package', $data);
			}
			else {
				$this->data->view = View::forge('frontend/no_package');
			}
		}
	}

	public function action_lost_password()
	{
		$this->data->leftcol = '';
		$data = array('error'=>\Fuel\Core\Input::get('error'));
		$this->data->view = View::forge('frontend/forgot_password', $data);
	}

	public function action_lost_password_attempt()
	{
		$email = \Fuel\Core\Input::post('email');
		Session::set_flash('email', $email);
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$user = Model_User::find('first',array(
				'where' => array('email'=>$email)
			));
			if($user==null) {
				return \Fuel\Core\Response::redirect('/forgot-password?error=2');
			} else {
				$new_password = Auth::reset_password($user->username);

				$data = array(
					'newpwd' => $new_password
				);
				$this->mail->toUser($user, \Fuel\Core\View::forge('mail/new_password', $data),'RPGBOSS Asset Server - Request a new password');

				$this->data->leftcol = '';
				$this->data->view = View::forge('frontend/forgot_password_success');
			}
		} else {
			return \Fuel\Core\Response::redirect('/forgot-password?error=1');
		}
	}

	public function action_show_user()
	{
		$data = array();
		$data['user'] = Model_User::find($this->param('userid'));

		$this->data->leftcol = '';

		if($data['user']==null) {
			$this->data->view = View::forge('frontend/no_user');
		} else {
			$data['packages'] = Model_Package::find('all', array(
				'where' => array('user_id'=>$this->param('userid'), 'verified'=>1),
				'order_by' => array('created_at'=>'DESC')
			));
			$data['projects'] = Model_Project::find('all', array(
				'where' => array('user_id'=>$this->param('userid'), 'verified'=>0),
				'order_by' => array('created_at'=>'DESC')
			));

			$this->data->view = View::forge('profile/public', $data);
		}

	}

	public function action_view_category()
	{
		$data = array();
		$data['category'] = Model_Category::find('first',array(
			'where' => array('slug'=>$this->param('catslug'))
		));

		if($data['category']==null) {
			$this->data->view = View::forge('frontend/no_category');
		} else {

			$data['packages'] = Model_Package::find('all', array(
				'where' => array('category_id'=>$data['category']->id, 'verified'=>1),
				'order_by' => array('created_at'=>'DESC')
			));

			$this->data->view = View::forge('frontend/category', $data);
		}


	}

	public function action_404()
	{
		$this->data->view = View::forge('404');
	}
}
