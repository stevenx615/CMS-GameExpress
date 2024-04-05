<?php
session_start();
require 'utilities.php';
require 'authentication.php';
require 'db_connection.php';

// validate post Id
if (!validatePostId()) {
  redirect('index.php');
}

// the words to filter in the comment
$filter_words = ['move', 'away', 'money'];

$post_id = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

// retrieve posts
$query = "SELECT * 
          FROM posts p
            JOIN users u ON p.author_id = u.user_id
            JOIN categories c ON p.category_id = c.category_id 
          WHERE p.post_id = :post_id";
try {
  $statement = $db_conn->prepare($query);
  $statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('There is an error when retrieving posts.');
}

// no data was found
if (empty($result)) {
  redirect('index.php');
}

// validate the post Id in URL 
function validatePostId()
{
  $valid = true;
  if (empty($_GET['pid'])) {
    $valid = false;
  } elseif (!filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT)) {
    $valid = false;
  }
  return $valid;
}

// disemvowel specific words within a comment
function disemvowel_comment($comment, $filter_words)
{
  foreach ($filter_words as $word) {
    $disemvoweled_word = str_replace(['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'], '*', $word);
    $comment = str_replace($word, $disemvoweled_word, $comment);
  }
  return $comment;
}

// recursive function to the replies
function displayReply($parent_id, $post_id, $db_conn)
{
  global $filter_words;
  $query = "SELECT * FROM comments WHERE comment_parent_id = :parent_id AND post_id = :post_id ORDER BY comment_id DESC";
  $statement = $db_conn->prepare($query);
  $statement->bindValue("parent_id", $parent_id, PDO::PARAM_INT);
  $statement->bindValue("post_id", $post_id, PDO::PARAM_INT);
  $statement->execute();
  $reply_rows = $statement->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reply_rows)) {
    return;
  }

  $full_name = '';
  if (is_logged_in()) {
    $full_name = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
  }
  $html = '';
  foreach ($reply_rows as $reply) {
    $html .= '<div class="comment-row" id="c' . $reply['comment_id'] . '">';
    $html .= '    <div class="comment-left-col">';
    $html .= '        <img class="comment-user-avatar" src="images/user-avatar.png" alt="User Picture">';
    $html .= '    </div>';
    $html .= '    <div class="comment-right-col">';
    $html .= '        <div><span class="comment-author">' . $reply['comment_username'] . '</span>';
    $html .= '        <span class="comment-created-date">' . $reply['comment_created_date'] . '</span></div>';
    $html .= '        <div class="comment-display">' . disemvowel_comment($reply['comment_content'], $filter_words) . '</div>';
    $html .= '        <div class="comment-tool"><a onclick="createReplyInput(' . $reply['comment_id'] . ' ,' . $post_id . ', \'' . $full_name . '\')" href="javascript:void(0);" class="comment-reply">Reply</a>';
    if (is_logged_in() && has_role([1])) {
      $html .= '          <a href="comment-process.php?action=delete&pid=' . $post_id . '&cid=' . $reply['comment_id'] . '" class="comment-delete">Delete</a>';
    }
    $html .= '        </div>';
    $html .= '        <div class="comment-reply-input" id="comment_reply_input_' . $reply['comment_id'] . '"></div>';
    $html .= '        <div class="comment-reply-row">';
    $html .= displayReply($reply['comment_id'], $post_id, $db_conn);
    $html .= '        </div>';
    $html .= '    </div>';
    $html .= '</div>';
  }

  return $html;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - <?= $result['post_title'] ?></title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  include "template-search.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap">
      <article class="mb-5">
        <section class="view-heading row mt-5">
          <nav class="breadcrumb">
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="search.php?cid=<?= $result['category_id'] ?>"><?= $result['category_name'] ?></a></li>
            </ul>
          </nav>
          <h1><?= $result['post_title'] ?></h1>
          <div class="heading-author">By <span
              class="author-text"><?= $result['first_name'] . ' ' . $result['last_name'] ?></span>
            <span class="ms-3">published on <?= date('F d, Y', strtotime($result['post_modified_date'])) ?></span>
          </div>
          <?php if (!empty($result['post_image'])) : ?>
          <div class="heading-image">
            <img src="<?= $result['post_image'] ?>" alt="Game image">
          </div>
          <?php endif ?>
        </section>
        <section class="post-content">
          <?= $result['post_content'] ?>
        </section>
      </article>
      <hr>
      <section class="comment mt-5" id="comment">
        <?php
        $comment_count_query = "SELECT COUNT(*) FROM comments c WHERE post_id = :post_id";
        try {
          $statement = $db_conn->prepare($comment_count_query);
          $statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
          $statement->execute();
          $comment_count = $statement->fetchColumn();
        } catch (PDOException $e) {
          die('There is an error when counting comments.');
        }

        ?>
        <h3>Comments (<?= $comment_count ?>)</h3>
        <div class="mt-1 p-3 bg-dark rounded-2">Notes: Words such as "<span class="text-danger">move</span>, <span
            class="text-danger">away</span>,
          <span class="text-danger">money</span>" will be filtered out of
          comments.
        </div>
        <h5 class="mt-5">Leave your comment</h5>
        <form action="comment-process.php?action=add&pid=<?= $result['post_id'] ?>" method="POST" id="comment_form"
          onsubmit="validateForm(event, ['comment_username', 'comment_content']);">
          <div class="mt-2">
            <input type="text" name="comment_username" id="comment_username" placeholder="Name"
              value="<?= is_logged_in() ? $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] : '' ?>">
          </div>
          <div class="field_error mt-1" id="comment_username_error"></div>
          <div class="mt-2">
            <textarea name="comment_content" id="comment_content" placeholder="Write your comment here..."></textarea>
          </div>
          <div class="field_error mt-1" id="comment_content_error"></div>
          <div>
            <button type="submit" class="btn-green btn-submit mt-3">Post Comment</button>
          </div>
        </form>
        <div class="comment-list mt-5">
          <?php
          // retrieve posts
          $query = "SELECT * 
                    FROM comments c
                    LEFT JOIN users u ON c.author_id = u.user_id
                    WHERE c.post_id = :post_id AND c.comment_parent_id IS NULL ORDER BY c.comment_id DESC";
          try {
            $statement = $db_conn->prepare($query);
            $statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
            $statement->execute();
            $comment_rows = $statement->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
            die('There is an error when retrieving comments.');
          }

          ?>
          <?php foreach ($comment_rows as $comment_row) : ?>
          <div class="comment-row" id="c<?= $comment_row['comment_id'] ?>">
            <div class="comment-left-col">
              <img class="comment-user-avatar" src="images/user-avatar.png" alt="User Picture">
            </div>
            <div class="comment-right-col">
              <div><span class="comment-author"><?= $comment_row['comment_username'] ?></span>
                <span class="comment-created-date"><?= $comment_row['comment_created_date'] ?></span>
              </div>
              <div class="comment-display"><?= disemvowel_comment($comment_row['comment_content'], $filter_words) ?>
              </div>
              <div class="comment-tool">
                <a onclick="createReplyInput(<?= $comment_row['comment_id'] ?>, <?= $post_id ?>, '<?= is_logged_in() ? $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] : '' ?>')"
                  href="javascript:void(0);" class="comment-reply">Reply</a>
                <?php if (is_logged_in() && has_role([1])) : ?>
                <a href="comment-process.php?action=delete&pid=<?= $post_id ?>&cid=<?= $comment_row['comment_id'] ?>"
                  class="comment-delete">Delete</a>
                <?php endif ?>
              </div>
              <div class="comment-reply-input" id="comment_reply_input_<?= $comment_row['comment_id'] ?>"></div>
              <div class=" comment-reply-row">
                <?= displayReply($comment_row['comment_id'], $post_id, $db_conn) ?>
              </div>
            </div>
          </div>
          <?php endforeach ?>

        </div>
      </section>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
  <script>
  // create a comment input area
  function createReplyInput(commentId, postId, fullName) {
    const form_check = document.querySelector("#comment_form_" + commentId);
    if (form_check) {
      form_check.remove();
      return;
    }

    var form = document.createElement("form");
    form.setAttribute("action", `comment-process.php?action=add&pid=${postId}&cid=${commentId}`);
    form.setAttribute("method", "POST");
    form.setAttribute("id", "comment_form_" + commentId);
    form.setAttribute("class", "comment_reply_form");
    form.onsubmit = function(event) {
      validateForm(event, ['comment_username_' + commentId, 'comment_content_' + commentId]);
    };

    var inputUsername = document.createElement("input");
    inputUsername.setAttribute("type", "text");
    inputUsername.setAttribute("name", "comment_username");
    inputUsername.setAttribute("id", "comment_username_" + commentId);
    inputUsername.setAttribute("placeholder", "Name");
    inputUsername.value = fullName;

    var divUsernameError = document.createElement("div");
    divUsernameError.setAttribute("class", "field_error mt-1");
    divUsernameError.setAttribute("id", `comment_username_${commentId}_error`);

    var textareaContent = document.createElement("textarea");
    textareaContent.setAttribute("class", "mt-2");
    textareaContent.setAttribute("name", "comment_content");
    textareaContent.setAttribute("id", "comment_content_" + commentId);
    textareaContent.setAttribute("placeholder", "Write your comment here...");

    var divContentError = document.createElement("div");
    divContentError.setAttribute("class", "field_error mt-1");
    divContentError.setAttribute("id", `comment_content_${commentId}_error`);

    // Create submit button
    var submitButton = document.createElement("button");
    submitButton.setAttribute("type", "submit");
    submitButton.setAttribute("class", "btn-green btn-submit mt-0");
    submitButton.textContent = "Post Comment";

    // Append elements to the form
    form.appendChild(inputUsername);
    form.appendChild(divUsernameError);
    form.appendChild(textareaContent);
    form.appendChild(divContentError);
    form.appendChild(submitButton);

    document.getElementById("comment_reply_input_" + commentId).appendChild(form)
  }
  </script>
</body>

</html>