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

        // Set headings
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
                // Inject the default form
                $trash = Form::open("Admin", array("method" => "post"));
                $trash .= Form::hidden("id", $comment['id']);
                $trash .= Form::hidden("action", 'trash');
                $trash .= Form::button(NULL, '<span class="glyphicon glyphicon-trash"></span>', array("class" => "btn btn-danger btn-sm", "type" => "submit", "title" => "Trash comment"));
                $trash .= Form::close();

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
        $this->template->messages = array();
        $comments = $comment = Model::factory("comment");

        // Process forms
        if (!empty($_POST['action']) AND !empty($_POST['id']))
        {
            // Get the comment
            $getComment = current($comments->id($_POST['id']));

            // Switch through the cases
            switch ($_POST['action'])
            {
                // Trash request
                case 'trash':
                    // Confirmation form
                    $this->template->content = Form::open("Admin", array("class" => "form form-vertical well", "method" => "post"));
                    $this->template->content .= "<h4>Confirm Comment Deletion</h4>";

                    if (!empty($getComment))
                    {
                        $this->template->content .= "<p><b>AUTHOR</b> " . $getComment['first_name'] . "<br />";
                        $this->template->content .= "<b>DATE</b> " . $getComment['date'] . "<br />";
                        $this->template->content .= "<b>COMMENT</b> " . $getComment['comment'] . "</p>";
                    }

                    $this->template->content .= Form::hidden("id", $_POST['id']);
                    $this->template->content .= Form::hidden("action", 'trash-confirmed');
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

                    // Check for a submit and process the values
                    if (!empty($_POST['confirm']) AND $_POST['confirm'] == "Save")
                    {
                        // Start the validation engine up
                        $object = Validation::factory($_POST);
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
                                "id"            => HTML::chars(HTML::entities($_POST['id'])),
                                "first_name"    => HTML::chars(HTML::entities($_POST['first_name'])),
                                "email"         => HTML::chars(HTML::entities($_POST['email'])),
                                "comment"       => HTML::chars(HTML::entities($_POST['comment'])),
                            );
                            var_dump($updateValues);
                            $display = false;
                        }

                        // Failure
                        else
                        {
                            $this->template->messages["error"][] = "Fields are invalid";
                        }
                    }

                    // Only display when not posted / validated / saved
                    if ($display)
                    {
                        // Confirmation form
                        $this->template->content = Form::open("Admin", array("class" => "form form-vertical well", "method" => "post"));
                        $this->template->content .= "<h4>Update Comment</h4>";
                        $this->template->content .= Form::hidden("id", $_POST['id']);
                        $this->template->content .= Form::hidden("action", 'edit');

                        $this->template->content .= '<div class="form-group">';
                        $this->template->content .= Form::input("first_name", $getComment['first_name'], array("class" => "form-control", "type" => "text", "placeholder" => "John Doe"));
                        $this->template->content .= "</div>";

                        $this->template->content .= '<div class="form-group">';
                        $this->template->content .= Form::input("email", $getComment['email'], array("class" => "form-control", "type" => "text", "placeholder" => "john.doe@mail.com"));
                        $this->template->content .= "</div>";

                        $this->template->content .= '<div class="form-group">';
                        $this->template->content .= Form::textarea("comment", str_replace("<br />", "\n", $getComment['comment']), array("class" => "form-control", "type" => "text", "placeholder" => "Comment"));
                        $this->template->content .= "</div>";

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
                        $trash = $comments->trash($_POST['id']);

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


                default:
                    break;
            }
        }
    }
}