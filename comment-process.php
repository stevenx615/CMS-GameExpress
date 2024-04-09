<?php
session_start();
require 'utilities.php';
require 'authentication.php';

//check permission
// if (!(is_logged_in())) {
//   redirect('index.php');
// }

if (!isset($_GET['action'])) {
  redirect('index.php');
}

$action = $_GET['action'];

require 'db_connection.php';

switch ($action) {
  case 'add': {
      $error_msgs = add_comment_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('error.php?action=error-messages');
      }

      // handle the reply
      $comment_id = filter_input(INPUT_GET, 'cid', FILTER_SANITIZE_NUMBER_INT);
      if (!empty($comment_id)) {
        $reply_query_column = ', comment_parent_id';
        $reply_query_value = ', :comment_id';
      }

      $post_id = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
      $username = filter_input(INPUT_POST, 'comment_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $content = sanitizeComment($_POST['comment_content']);
      $created_date = date('Y-m-d H:i:s');

      // if the user logged in
      $author_query_column = '';
      $author_query_value = '';
      if (is_logged_in()) {
        $author_id = $_SESSION['user']['user_id'];
        $author_query_column = ', author_id';
        $author_query_value = ', :author_id';
      }

      $query = "INSERT INTO comments (comment_content, comment_created_date, comment_username, post_id" . $author_query_column . $reply_query_column . ") VALUES (:content, :created_date, :username, :post_id" . $author_query_value . $reply_query_value . ")";

      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':created_date', $created_date);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        if (!empty($author_query_column)) {
          $statement->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        }
        if (!empty($reply_query_column)) {
          $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        }
        $statement->execute();
        $comment_id = $db_conn->lastInsertId();
        redirect('view.php?pid=' . $post_id . '#c' . $comment_id);
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        die('There is an error when creating a new post.');
      }
      break;
    };
  case 'delete': {
      $error_msgs = delete_comment_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('error.php?action=error-messages');
      }

      $post_id = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
      $comment_id = filter_input(INPUT_GET, 'cid', FILTER_SANITIZE_NUMBER_INT);
      $query = "DELETE FROM comments WHERE comment_id = :comment_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':comment_id', $comment_id);
        $statement->execute();
        redirect('view.php?pid=' . $post_id . '#comment');
      } catch (PDOException $e) {
        die('There is an error when deleting the comment.');
      }
      break;
    };
  default:
    redirect('index.php');
    break;
}

// Sanitize the comment
function sanitizeComment($comment)
{
  $safeComment = '';
  $safeComment = htmlspecialchars($comment, ENT_QUOTES);
  $safeComment = filter_var($safeComment, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  return $safeComment;
}

function add_comment_validation()
{
  $error_msgs = [];
  if (!isset($_POST['comment_username'])) {
    $error_msgs[] = 'Username is required.';
  } elseif (empty(trim($_POST['comment_username']))) {
    $error_msgs[] = 'Username is required.';
  }
  if (!isset($_POST['comment_content'])) {
    $error_msgs[] = 'Content is required.';
  } elseif (empty(trim($_POST['comment_content']))) {
    $error_msgs[] = 'Content is required.';
  }
  if (empty($_GET['pid'])) {
    $error_msgs[] = 'Post ID is required.';
  } elseif (!filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT)) {
    $error_msgs[] = 'Post ID is required.';
  }
  if (!empty($_GET['cid'])) {
    if (!filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT)) {
      $error_msgs[] = 'Comment ID is invalid.';
    }
  }
  return $error_msgs;
}

function delete_comment_validation()
{
  $error_msgs = [];
  if (empty($_GET['pid'])) {
    $error_msgs[] = 'Post ID is required.';
  } elseif (!filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT)) {
    $error_msgs[] = 'Post ID is required.';
  }
  if (empty($_GET['cid'])) {
    $error_msgs[] = 'Comment ID is required.';
  } elseif (!filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT)) {
    $error_msgs[] = 'Comment ID is invalid.';
  }
  return $error_msgs;
}
