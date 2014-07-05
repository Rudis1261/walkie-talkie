<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Template {

    // Making use of the home template view
    public $template = 'admin';

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

                // Trash confirmation
                case 'trash-confirmed':
                    if (isset($_POST['confirm']) AND $_POST['confirm'] == "Yes")
                    {
                        $trash = $comments->trash($_POST['id']);

                        if ($trash == true)
                        {
                            $this->template->messages["info"][] = "Successfully deleted comment";
                        }

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
}