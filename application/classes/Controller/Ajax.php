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
            $json->error('No comments yet, get the party started');
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
            // Set the data and return success response
            $json->data($getData);
            $json->success('Changes provided');
        }
    }

    // We also need to be able to add more comments
    public function action_add()
    {
        // Select all the
        $comment = Model::factory("comment");
        $json = Model::factory("json");

        // Clean POST values. Strip out tags and white spaces primarily
        $clean = $comment->cleanse($_POST);

        // Start the validation engine up
        $object = Validation::factory($clean);
        $object
            ->rule('first_name', 'not_empty')
            ->rule('first_name', 'min_length', array(':value', '2'))
            ->rule('first_name', 'max_length', array(':value', '30'))
            ->rule('comment', 'not_empty')
            ->rule('comment', 'min_length', array(':value', '6'))
            ->rule('comment', 'max_length', array(':value', '1000'))
            ->rule('email', 'not_empty')
            ->rule('email', 'email_domain');

        // Validate the post information
        $valid = $object->check();

        // Valid
        if ($valid == TRUE)
        {
            // We can now try and insert the comment
            // The parent isn't strictly checked, if nothing is received ensure that we insert a null
            $getParent = (isset($clean['parent'])) ? $clean['parent'] : 0;

            // Build the info to insert
            $insertValues = array(
                "id"            => NULL,
                "parent"        => $getParent,
                "first_name"    => $clean['first_name'],
                "email"         => $clean['email'],
                "comment"       => $clean['comment'],
                "timestamp"     => time(),
                "active"        => 1
            );

            // Try and see if we can insert it
            $insert = $comment->add($insertValues);

            // Did we succeed?
            if ($insert == TRUE)
            {
                // All done
                $json->success("Successfully added comment");
            }

            // Did we fail to insert the new comment?
            else
            {
                // Show why the insert failed
                $json->error($insert);
            }
        }

        // Invalid data
        else
        {
            # Get the errors from the validation class
            $getErrors = array();
            if (!empty($object->errors()))
            {
                $getErrors = array_keys($object->errors());
            }

            // Add the error messages
            $json->data($getErrors);
            $json->error("ERROR");
        }
    }
}