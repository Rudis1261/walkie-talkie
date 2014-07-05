<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Template {

    // Making use of the home template view
    public $template = 'admin';

    // This is the default entry point
    public function action_index()
    {
        // Prepare the page and send the details to the template
        $this->template->title = "Convo";
        $this->template->icon = "comment";
        $this->template->content = "";

        // Process the forms
        $this->process_forms();

        // We need to get a list of all the comments
        $comments = $comment = Model::factory("comment");
        $getData = $comment->all("id", "DESC");

        // Fire up the table helper
        $table = Model::factory("table");
        $output = "No results found";

        // Set the table headings
        $table->headings(
            array(
                "ID",
                array(
                    "value" => "PID",
                    "title" => "Parent ID"
                ),
                "Detail",
                "Comment",
                "Actions"
            )
        );

        // Ensure that the data we get is not empty
        if(!empty($getData))
        {
            // loop through the comments and create the table
            foreach($getData as $comment)
            {
                // Inject the default forms
                // Trash "delete" button form
                $trash = Form::open("Admin", array("method" => "post"));
                $trash .= Form::hidden("id", $comment['id']);
                $trash .= Form::hidden("action", 'trash');
                $trash .= Form::button(NULL, '<span class="glyphicon glyphicon-trash"></span>', array("class" => "btn btn-danger btn-sm", "type" => "submit", "title" => "Trash comment"));
                $trash .= Form::close();

                // Edit button form
                $edit = Form::open("Admin", array("method" => "post"));
                $edit .= Form::hidden("id", $comment['id']);
                $edit .= Form::hidden("action", 'edit');
                $edit .= Form::button(NULL, '<span class="glyphicon glyphicon-pencil"></span>', array("class" => "btn btn-default btn-sm", "type" => "submit", "title" => "Edit comment"));
                $edit .= Form::close();

                // Add the row
                $table->addRow(
                    array(
                        $comment['id'],
                        $comment['parent'],
                        $comment['first_name']. "<br />" .$comment['email']. "<br />" .$comment['date'],
                        $comment['comment'],
                        $edit . $trash
                    )
                );
            }

            // Render the table out
            $output = $table->render();
        }

        # Add the output
        $this->template->comments = $output;
    }

    // We need a simple way to process forms
    public function process_forms()
    {
        // Set a default message array
        $this->template->messages = array();

        // Hook into the comment Model
        $comments = $comment = Model::factory("comment");

        // Process forms
        if (!empty($_POST['action']) AND !empty($_POST['id']))
        {
            // Clean post details up
            $clean = $comments->cleanse($_POST);

            // Get the comment
            $getComment = current($comments->id($clean['id']));

            // Switch through the cases
            switch ($_POST['action'])
            {
                // Trash request
                case 'trash':

                    // Confirmation form
                    $this->template->content = Form::open("Admin", array("class" => "form form-vertical well", "method" => "post"));
                    $this->template->content .= "<h4>Confirm Comment Deletion</h4>";

                    // Ensure that we have a comment to get the information from
                    if (!empty($getComment))
                    {
                        // Add the content
                        $this->template->content .= "<p><b>AUTHOR</b> " . $getComment['first_name'] . "<br />";
                        $this->template->content .= "<b>DATE</b> " . $getComment['date'] . "<br />";
                        $this->template->content .= "<b>COMMENT</b> " . nl2br($getComment['comment']) . "</p>";
                    }

                    // We need some hidden content
                    $this->template->content .= Form::hidden("id", $clean['id']);
                    $this->template->content .= Form::hidden("action", 'trash-confirmed');

                    // And to group the confirm buttons
                    $this->template->content .= '<div class="form-group">';
                    $this->template->content .= Form::input("confirm", "Yes", array("class" => "btn btn-default btn-lg", "type" => "submit"));
                    $this->template->content .= "&nbsp;&nbsp;";
                    $this->template->content .= Form::input("confirm", "No", array("class" => "btn btn-danger btn-lg", "type" => "submit"));
                    $this->template->content .= "</div>";

                    $this->template->content .= Form::close();
                    break;

                // Trash request
                case 'edit':

                    // Should the form be displayed?
                    $display = true;

                    // Set the default values
                    $values = array(
                        "first_name" => $getComment['first_name'],
                        "email" => $getComment['email'],
                        "comment" => $getComment['comment']
                    );

                    // Set blank errors
                    $errors = array(
                        "first_name" => array(
                            "class" => "",
                            "message" => "",
                            "min" => 2,
                            "max" => 30
                        ),
                        "email" => array(
                            "class" => "",
                            "message" => "",
                            "min" => 0,
                            "max" => 0
                        ),
                        "comment" => array(
                            "class" => "",
                            "message" => "",
                            "min" => 6,
                            "max" => 1000
                        )
                    );

                    // Check for a submit and process the values
                    if (!empty($_POST['confirm']) AND $_POST['confirm'] == "Save")
                    {
                        // Assign the new values from the post
                        foreach($values as $key => $value)
                        {
                            // Only use the indexes we are looking for
                            $values[$key] = $clean[$key];
                        }

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
                            // Build the info to insert
                            $updateValues = array(
                                "id"            => $clean['id'],
                                "first_name"    => $clean['first_name'],
                                "email"         => $clean['email'],
                                "comment"       => $clean['comment']
                            );

                            // Attempt to update the comment
                            $doUpdate = $comments->edit($updateValues);

                            // Success
                            if ($doUpdate)
                            {
                                $this->template->messages["info"][] = "Changes Saved";
                            }

                            // Failure
                            else
                            {
                                $this->template->messages["error"][] = "Could not update comment";
                            }

                            // Hide the form as complete
                            $display = false;
                        }

                        // Failure
                        else
                        {
                            // Check that there were errors indeed
                            if (!empty($object->errors()))
                            {
                                // Loop through them
                                foreach($object->errors() as $key => $error)
                                {
                                    // Custom error messages
                                    switch (current($error))
                                    {
                                        case 'not_empty':
                                            $message = "Field cannot be empty";
                                            break;

                                        case 'min_length':
                                            $message = "Field needs to be at least ". $errors[$key]["min"] ." in length";
                                            break;

                                        case 'max_length':
                                            $message = "Field needs to be a maximum of ". $errors[$key]["max"] ." in length";
                                            break;

                                        case 'email_domain':
                                            $message = "Email Address domain does not exist, address appears to be invalid";
                                            break;

                                        default:
                                            $message = "Application issue, error unknown";
                                            break;
                                    }

                                    // Update the errors
                                    $errors[$key]['class'] = "has-error";
                                    $errors[$key]['message'] = $message;
                                }
                            }

                            // Set the error to indicate there is issues
                            $this->template->messages["error"][] = "Some errors were encountered, details below";
                        }
                    }

                    // Only display when not posted / validated / saved
                    if ($display)
                    {
                        // Edit form, set default values. overriden with post values. including error messages and states
                        $this->template->content = Form::open("Admin", array("class" => "form form-vertical well", "method" => "post"));
                        $this->template->content .= "<h4>Update Comment</h4>";
                        $this->template->content .= Form::hidden("id", $clean['id']);
                        $this->template->content .= Form::hidden("action", 'edit');

                        $this->template->content .= '<div class="form-group '. $errors['first_name']['class'] .'">';
                        $this->template->content .= Form::label("Name", NULL, array("class" => "input-label"));
                        $this->template->content .= Form::input("first_name", $values['first_name'], array("class" => "form-control", "type" => "text", "placeholder" => "John Doe"));
                        $this->template->content .= "<span class='help-block'>". $errors['first_name']['message'] ."</span></div>";

                        $this->template->content .= '<div class="form-group '. $errors['email']['class'] .'">';
                        $this->template->content .= Form::label("Email Address", NULL, array("class" => "input-label"));
                        $this->template->content .= Form::input("email", $values['email'], array("class" => "form-control", "type" => "text", "placeholder" => "john.doe@mail.com"));
                        $this->template->content .= "<span class='help-block'>". $errors['email']['message'] ."</span></div>";

                        $this->template->content .= '<div class="form-group '. $errors['comment']['class'] .'">';
                        $this->template->content .= Form::label("Comment", NULL, array("class" => "input-label"));
                        $this->template->content .= Form::textarea("comment", $values['comment'], array("class" => "form-control", "type" => "text", "placeholder" => "Comment"));
                        $this->template->content .= "<span class='help-block'>". $errors['comment']['message'] ."</span></div>";

                        $this->template->content .= '<div class="form-group">';
                        $this->template->content .= Form::input("confirm", "Save", array("class" => "btn btn-danger btn-lg", "type" => "submit"));
                        $this->template->content .= "</div>";
                        $this->template->content .= Form::close();
                    }
                    break;

                // Trash confirmation
                case 'trash-confirmed':
                    if (isset($_POST['confirm']) AND $_POST['confirm'] == "Yes")
                    {
                        // Try and delete the trash
                        $trash = $comments->trash($clean['id']);

                        // Success
                        if ($trash == true)
                        {
                            $this->template->messages["info"][] = "Successfully deleted comment";
                        }

                        // Failure
                        else
                        {
                            $this->template->messages["error"][] = "Failed to delete comment, it's most likely already deleted";
                        }
                    }
                    break;

                // Empty default
                default:
                    break;
            }
        }
    }
}