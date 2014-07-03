<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

    // Get function
    public function action_comments()
    {
        # Select all the
        $comment = Model::factory("comment");
        $json = Model::factory("json");
        $getData = $comment->all();

        // Valid response
        if (!empty($getData))
        {
            $json->data($getData);
            $json->success('Changes provided');
        }

        // Failure
        else
        {
            $json->error('Empty');
        }
    }

    // Get only changes
    public function action_since()
    {
        # Select all the
        $comment = Model::factory("comment");
        $json = Model::factory("json");
        $params = $this->request->param();
        $getData = $comment->since($params['id']);

        // Valid response
        if (!empty($getData))
        {
            $json->data($getData);
            $json->success('Changes provided');
        }

        // Failure
        else
        {
            $json->error('Empty');
        }
    }
}