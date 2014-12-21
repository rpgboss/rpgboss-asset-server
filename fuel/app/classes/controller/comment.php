<?php
/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Comment extends \LayoutController
{

    public function before()
    {
        parent::before();
        $userid = \Auth\Auth::get_user_id();
        $this->data->userid = $userid['1'];
        $this->redirectIfNotLoggedIn();
    }

    public function action_add()
    {

        $this->no_render();

        $packageid = $this->param('packageid');
        $comment = new Model_Comment();
        $comment->package_id = $packageid;
        $comment->user_id = $this->data->userid;
        $comment->rating = Input::Post('rating');
        $comment->content = Input::Post('text');
        $comment->created_at = time();
        $comment->save();

        \Fuel\Core\Response::redirect(\Fuel\Core\Input::get('redirect'));
    }

}
