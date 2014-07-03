<?php defined('SYSPATH') or die('No direct script access.');

class Model_comment extends Model {

    // We need to get all the comments at once
    public function all()
    {
        // We will fill this if there is any data
        $output = array();

        // Select all the data
        $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->execute()->as_array();

        // We need some content
        if (!empty($result))
        {
            $output = $result;
        }

        // Just return the output
        return $output;
    }

    // We need to get all the comments at once
    public function since($timestamp)
    {
        // We will fill this if there is any data
        $output = array();

        // Select all the data since the last date
        $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->where('timestamp', '>=', $timestamp)->execute()->as_array();

        // We need some content
        if (!empty($result))
        {
            $output = $result;
        }

        // Just return the output
        return $output;
    }
}