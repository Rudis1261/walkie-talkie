<?php defined('SYSPATH') or die('No direct script access.');

class Model_comment extends Model {

    // We need to get all the comments at once
    public function all($order=false, $direction=false)
    {
        // We will fill this if there is any data
        $output = array();

        // Select all the data
        if ($order == false AND $direction == false)
        {
            $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->where('active', "=", 1)->execute()->as_array();
        }

        else
        {
            $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->where('active', "=", 1)->order_by($order, $direction)->execute()->as_array();
        }

        // We need some content
        if (!empty($result))
        {
            // Transform the data somewhat
            $output = $this->transform($result, true);
        }

        // Just return the output
        return $output;
    }

    // I want a particular id
    public function id($id)
    {
        // We will fill this if there is any data
        $output = array();

        // Select all the data
        $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->where('id', "=", $id)->execute()->as_array();

        // We need some content
        if (!empty($result))
        {
            // Transform the data somewhat
            $output = $this->transform($result);
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
        $result = DB::select('id', 'parent',  'first_name', 'email', 'comment', 'timestamp')->from('comments')->where('active', "=", 1)->and_where('timestamp', '>=', $timestamp)->execute()->as_array();

        // We need some content
        if (!empty($result))
        {
            // Transform the data somewhat
            $output = $this->transform($result, true);
        }

        // Just return the output
        return $output;
    }

    // Insert a new comment
    public function add($inputArray)
    {
        $output = "Could not add new Comment";

        // Try the insert
        try
        {
            DB::insert('comments', array('id', 'parent',  'first_name', 'email', 'comment', 'timestamp', 'active'))->values($inputArray)->execute();
            $output = true;
        }

        // Catch any exceptions we may encounter
        catch ( Database_Exception $e )
        {
            $output = $e->getMessage();
        }

        // Return the result
        return $output;
    }

    // Update function
    public function edit($inputArray)
    {
        $output = "Could not edit the Comment";

        // Try the insert
        try
        {
            DB::update('comments')->set(array('first_name'=>$inputArray['first_name'], 'email'=>$inputArray['email'], 'comment'=>$inputArray['comment']))->where('id','=',$inputArray['id'])->execute();
            //DB::insert('comments', array('id', 'parent',  'first_name', 'email', 'comment', 'timestamp', 'active'))->values($inputArray)->execute();
            $output = true;
        }

        // Catch any exceptions we may encounter
        catch ( Database_Exception $e )
        {
            $output = $e->getMessage();
        }

        // Return the result
        return $output;
    }

    // Trash the comment
    public function trash($id)
    {
        return DB::update('comments')->set(array('active'=>'0'))->where('id','=',$id)->execute();
    }

    // Cleanse the input before we save it
    public function cleanse($inputArray)
    {
        $output = array();

        // Check that we have an input to work with
        if (!empty($inputArray) AND is_array($inputArray))
        {
            // Cleanse the details somewhat
            foreach ($inputArray as $index => $array)
            {
                $output[$index] = strip_tags($array);
                $output[$index] = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $output[$index]);
                $output[$index] = htmlspecialchars($output[$index]);
                $output[$index] = trim($output[$index]);
                $output[$index] = HTML::entities($output[$index]);
            }
        }

        // Return output
        return $output;
    }

    // Transform the array somewhat
    public function transform($inputArray=false, $break=false)
    {
        // Only attempt to transform the array should we actually have one
        if(!empty($inputArray))
        {
            // Loop through and add some information to the array
            foreach( (array) $inputArray as $index => $array)
            {
                // Add the date and string version
                if ($break)
                {
                    $inputArray[$index]['comment'] = nl2br(html_entity_decode($array['comment'], ENT_QUOTES));
                }
                else
                {
                    $inputArray[$index]['comment'] = html_entity_decode($array['comment'], ENT_QUOTES);
                }
                $inputArray[$index]['first_name'] = html_entity_decode($array['first_name'], ENT_QUOTES);
                $inputArray[$index]['age'] = $this->time2str($array['timestamp']);
                $inputArray[$index]['date'] = date('d F Y, H:i', $array['timestamp']);
            }
        }
        return $inputArray;
    }

    // Returns an English representation of a date
    // Graciously stolen from http://ejohn.org/files/pretty.js
    public function time2str($ts)
    {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

        $diff = time() - $ts;
        if($diff == 0)
            return 'now';
        elseif($diff > 0)
        {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) return 'Yesterday';
            if($day_diff < 7) return $day_diff . ' days ago';
            if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
            if($day_diff < 60) return 'last month';
            $ret = date('F Y', $ts);
            return ($ret == 'December 1969') ? '' : $ret;
        }
        else
        {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';
            $ret = date('F Y', $ts);
            return ($ret == 'December 1969') ? '' : $ret;
        }
    }
}