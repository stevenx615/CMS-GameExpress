<?php
session_start();
// print_r($_SESSION);
require 'utilities.php';
require 'authentication.php';
require 'db_connection.php';
require 'user-preference.php';

$error_msgs = validation();
if (!empty($error_msgs)) {
  $_SESSION['error_msgs'] = $error_msgs;
  redirect('error.php?action=error-messages');
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];
}

// get user setting preference
$preference = get_preference();

$sortby_query = get_post_sortby_query($preference['post_sortby']);
$orderby_query = get_orderby_query($preference['orderby']);

$url_params = '?action=' . $action . '&';

$search_content = '';
handle_search_content($search_content, $url_params);

$field_where_query = '';
$field_selected = handle_field_selected($field_where_query, $search_content, $url_params);

$categories_where_query = '';
$category_id = handle_category_selected($categories_where_query, $url_params);

// retrieve posts
$posts_query = "SELECT * 
          FROM posts p
            JOIN users u ON p.author_id = u.user_id
            JOIN categories c ON p.category_id = c.category_id
            WHERE" . $field_where_query . $categories_where_query . $sortby_query . $orderby_query;

try {
  $posts_stmt = $db_conn->prepare($posts_query);
  $posts_stmt->execute();
} catch (PDOException $e) {
  die('There is an error when retrieving posts.');
}

// validate the essential parameters
function validation()
{
  $error_msgs = [];
  if (!empty($_GET['cid'])) {
    if (!filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT)) {
      $error_msgs[] = 'Category ID must be a number.';
    }
  }
  if (!empty($_POST['category_selected'])) {
    if (!filter_input(INPUT_POST, 'category_selected', FILTER_VALIDATE_INT)) {
      $error_msgs[] = 'Search Category ID must be a number.';
    }
  }
  if (!empty($_POST['field_selected'])) {
    $fields = ['title', 'author', 'content'];
    $field_selected = filter_input(INPUT_POST, 'field_selected', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!in_array($field_selected, $fields)) {
      $error_msgs[] = 'Properties is invalid.';
    }
  }
  return $error_msgs;
}

// validate the search content from search bar
// modify the original $search_content
// modify the original $url_params
function handle_search_content(&$search_content, &$url_params)
{
  if (!empty($_POST['search_content'])) {
    $search_content = filter_input(INPUT_POST, 'search_content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  } elseif (!empty($_GET['search'])) {
    $search_content = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }
  $search_content = trim($search_content);
  $url_params .= "search=" . $search_content . "&";
}

// validate the properties of post from search bar
// modify the original $field_where_query
// modify the original $url_params
function handle_field_selected(&$field_where_query, $search_content, &$url_params)
{
  $field = 'title';

  if (!empty($_POST['field_selected'])) {
    $field = filter_input(INPUT_POST, 'field_selected', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  } elseif (!empty($_GET['field'])) {
    $field = filter_input(INPUT_GET, 'field', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  $search_content = strtolower($search_content);
  switch ($field) {
    case 'title': {
        $field_where_query = " p.post_title LIKE '%" . $search_content . "%'";
        break;
      };
    case 'content': {
        $field_where_query = " p.post_content LIKE '%" . $search_content . "%'";
        break;
      };
    case 'author': {
        $field_where_query = " u.first_name LIKE '%" . $search_content . "%' OR u.last_name LIKE '%" . $search_content . "%'";
        break;
      };
    default: {
        $field_where_query = " 1=1";
        break;
      };
  }
  $url_params .= "field=" . $field . "&";
  return $field;
}

// validate the category ID from search bar
// modify the original $categories_where_query
// modify the original $url_params 
function handle_category_selected(&$categories_where_query, &$url_params)
{
  $cid = -1;
  if (!empty($_POST['category_selected'])) {
    $cid = intval($_POST['category_selected']);
  } elseif (!empty($_GET['cid'])) {
    $cid = intval($_GET['cid']);
  }
  if ($cid != -1) {
    $categories_where_query = " AND p.category_id = " . $cid;
    $url_params .= "cid=" . $cid . "&";
  }
  return $cid;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - Search</title>
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
      <?php
      if ($category_id != -1) {
        $search_category_query = "SELECT * FROM categories WHERE category_id = " . $category_id;
        try {
          $search_category_stmt = $db_conn->prepare($search_category_query);
          $search_category_stmt->execute();
          $search_category_row = $search_category_stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          die('There is an error when retrieving categories.');
        }
      }
      ?>
      <?php if ($action == 'search') : ?>
        <div class="mt-5 text-center">
          Search for <span class="fw-bold fs-4">"<?= $search_content ?>" </span><br> in the <?= $field_selected ?> of the <?= $category_id == -1 ? 'all categories' : $search_category_row['category_name'] . ' categories' ?>.
        </div>
      <?php endif ?>
      <div class="row mt-5" style="color:#929AA6;">
        <div class="col">
          <h1 class="post-category-title"><?= $action == 'search' ? 'Search' : $search_category_row['category_name'] ?></span>
        </div>
        <?php if (is_logged_in()) : ?>
          <div class="col text-end fs-6">
            <form name="list-preference-form" method="POST" action="user-preference.php<?= $url_params ?>">
              <label for="orderby">Sort by</label>
              <select class="custom-dropdown-light dropdown-orderby ms-1" name="post_sortby" id="post_sortby" onchange="this.form.submit()">
                <?php foreach (PostSortBy::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['post_sortby'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <select class="custom-dropdown-light dropdown-orderby ms-1 me-4" name="orderby" id="orderby" onchange="this.form.submit()">
                <?php foreach (OrderBy::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['orderby'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <select class="custom-dropdown-light dropdown-pagesize me-1" name="pagesize" id="pagesize" onchange="this.form.submit()">
                <?php foreach (PageSize::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['pagesize'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <label for="pagesize">per page</label>
            </form>
          </div>
        <?php endif ?>
        <hr class="post-title-line">
        <div class="row">
          <div class="col pe-5">
            <?php
            while ($row = $posts_stmt->fetch(PDO::FETCH_ASSOC)) :
            ?>
              <div class="post-row row">
                <div class="post-left col-4">
                  <?php if (!empty($row['post_thumbnail'])) : ?>
                    <a href="view.php?pid=<?= $row['post_id'] ?>"><img src="<?= $row['post_thumbnail'] ?>" class="post-thumbnail"></a>
                  <?php endif ?>
                </div>
                <div class="post-right col">
                  <div class="post-category"><?= $row['category_name'] ?></div>
                  <h2 class="post-title"><a href="view.php?pid=<?= $row['post_id'] ?>"><?= $row['post_title'] ?></a>
                  </h2>
                  <div class="post-author">By <span class="author-text"><?= $row['first_name'] . ' ' . $row['last_name'] ?></span><span class="ms-5"><?= date('F d, Y', strtotime($row['post_modified_date'])) ?></span></div>
                  <div></div>
                </div>
              </div>
              <hr class="post-line">
            <?php endwhile ?>
          </div>
          <div class="col-2">
            <?php
            include "template-sidebar-categories.php";
            ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>