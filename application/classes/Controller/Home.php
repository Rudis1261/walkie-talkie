<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Template {

    # Making use of the home template view
    public $template = 'home';

    # This is the default entry point
    public function action_index()
	{
        # Prepare the page and send the details to the template
        $this->template->title = "Walkie-talkie";
        $this->template->icon = "comment";
        $this->template->topics = array();
		$this->template->comments = array();
	}

}