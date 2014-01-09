<?php

class Controller_Comment extends Controller_Base
{
        private $_auth;
        private $_user_id;
        
        public function before()
        {
            parent::before();
            $this->_auth = Auth::instance();
            $userids = $this->_auth->get_user_id();
            $this->_user_id = $userids[1];
        }
        

	public function action_index()
	{
		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Comment &raquo; Index';
		$this->template->content = View::forge('comment/index', $data);
	}

	public function action_create()
	{
            if(! Auth::has_access('comment.create')){
                //Session::set_flash('error', 'Only registered users may add comments');
                Response::redirect("/") and die();
            }
            if (! Input::post('comment'))
            {
                Response::redirect('tutorials/'.Input::Post('tutorial_id'));
            }
            
            if (Input::method() == "POST"&&Security::check_token()) {
                $new_comment = new Model_Comment();
                $new_comment->comment = Input::post('comment');
                $new_comment->tutorial_id = Input::post('tutorial_id');
                $new_comment->user_id  = $this->_user_id;
                $new_comment->save();
                Response::redirect('tutorials/'. $new_comment->tutorial_id);
            }
            else {
                Response::redirect("/");
            }           
	}

	public function action_edit($id = null)
	{
            is_null($id) and Response::redirect('explore');
            
            $comment = Model_Comment::find($id);
            
            if($comment->user_id != $this->_user_id && !Auth::member(100)){
                Response::redirect("/tutorials/". $comment->tutorial_id) and die();
            }
            
             is_null($comment) and Response::redirect('explore');
             
             if (Input::method() == 'POST'&&Security::check_token()){
                $comment->comment = Input::post('msg');
                $comment->save();
                Response::redirect('/tutorials/'. $comment->tutorial_id);
            }         
            
	}

	public function action_view()
	{
		$data["subnav"] = array('view'=> 'active' );
		$this->template->title = 'Comment &raquo; View';
		$this->template->content = View::forge('comment/view', $data);
	}
        
        public function action_delete($id = null)
        {
            is_null($id) and Response::redirect('event');
            $comment = Model_Comment::find($id);
            if($comment->user_id != $this->_user_id && !Auth::member(50) && !Auth::member(100)){
                Response::redirect ("tutorials/view/".$comment->tutorial_id) and die();
            }
            is_null($comment) and Response::redirect("tutorials/view/".$comment->tutorial_id);
            $temp = $comment->tutorial_id;
            $comment->delete();
            Response::redirect("tutorials/view/".$comment->tutorial_id);
        }
        

}
