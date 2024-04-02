<?php
require_once 'utilities.php';

enum PostSortBy: string
{
  case ID = 'ID';
  case TITLE = 'Title';
  case AUTHOR = 'Author';
  case CATEGORY = 'Category';
  case CREATED_DATE = 'Created Date';
  case MODIFIED_DATE = 'Modified Date';
}

enum CategorySortBy: string
{
  case ID = 'ID';
  case NAME = 'Name';
}

enum UserSortBy: string
{
  case ID = 'ID';
  case USERNAME = 'Username';
  case NAME = 'Name';
  case EMAIL = 'Email';
  case ROLE = 'Role';
}

enum OrderBy: string
{
  case ASC = 'Ascending';
  case DESC = 'Descending';
}

enum PageSize: int
{
  case SIZE_1 = 5;
  case SIZE_2 = 10;
  case SIZE_3 = 20;
  case SIZE_4 = 50;
  case SIZE_5 = 100;
}

change_preference();
function change_preference()
{
  if (!empty($_POST)) {
    if (isset($_POST['orderby']) && isset($_POST['pagesize'])) {
      $preference = array(
        'orderby' => $_POST['orderby'],
        'pagesize' => $_POST['pagesize'],
      );
      if (!empty($_POST['post_sortby'])) $preference['post_sortby'] = $_POST['post_sortby'];
      if (!empty($_POST['category_sortby'])) $preference['category_sortby'] = $_POST['category_sortby'];
      if (!empty($_POST['user_sortby'])) $preference['user_sortby'] = $_POST['user_sortby'];
      setcookie('admin-preference', json_encode($preference), time() + 3600 * 24, "/");
      redirect(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
    }
  }
}


function check_preference(&$preference, $default_preference, $key)
{
  if (empty($preference[$key])) {
    $preference[$key] = $default_preference[$key];
    setcookie('admin-preference', json_encode($preference), time() + 3600 * 24, "/");
    redirect($_SERVER['REQUEST_URI']);
  }
}

function get_admin_preference()
{
  $preference = array();

  $default_preference = array(
    'orderby' => OrderBy::DESC->value,
    'pagesize' => PageSize::SIZE_1->value,
    'post_sortby' => PostSortBy::ID->value,
    'category_sortby' => CategorySortBy::ID->value,
    'user_sortby' => UserSortBy::ID->value,
  );

  // get the preference from cookie, if empty then reset to default preference
  if (!empty($_COOKIE['admin-preference'])) {
    $preference = json_decode($_COOKIE['admin-preference'], true);
    check_preference($preference, $default_preference, 'orderby');
    check_preference($preference, $default_preference, 'pagesize');
    check_preference($preference, $default_preference, 'post_sortby');
    check_preference($preference, $default_preference, 'category_sortby');
    check_preference($preference, $default_preference, 'user_sortby');
  } else {
    $preference = $default_preference;
    setcookie('admin-preference', json_encode($preference), time() + 3600 * 24);
    redirect($_SERVER['REQUEST_URI']);
  }
  return $preference;
}

// get the post sort by portion of the query
function get_post_sortby_query($sortby)
{
  $query = '';
  if ($sortby == PostSortBy::ID->value) $query = 'p.post_id';
  if ($sortby == PostSortBy::TITLE->value) $query = 'p.post_title';
  if ($sortby == PostSortBy::AUTHOR->value) $query = 'p.author_id';
  if ($sortby == PostSortBy::CATEGORY->value) $query = 'p.category_id';
  if ($sortby == PostSortBy::CREATED_DATE->value) $query = 'p.post_created_date';
  if ($sortby == PostSortBy::MODIFIED_DATE->value) $query = 'p.post_modified_date';
  return ' ORDER BY ' . $query;
}

// get the category sort by portion of the query
function get_category_sortby_query($sortby)
{
  $query = '';
  if ($sortby == CategorySortBy::ID->value) $query = 'category_id';
  if ($sortby == CategorySortBy::NAME->value) $query = 'category_name';
  return ' ORDER BY ' . $query;
}

// get the user sort by portion of the query
function get_user_sortby_query($sortby)
{
  $query = '';
  if ($sortby == UserSortBy::ID->value) $query = 'u.user_id';
  if ($sortby == UserSortBy::USERNAME->value) $query = 'u.username';
  if ($sortby == UserSortBy::NAME->value) $query = 'u.first_name';
  if ($sortby == UserSortBy::EMAIL->value) $query = 'u.email';
  if ($sortby == UserSortBy::ROLE->value) $query = 'u.role_id';
  return ' ORDER BY ' . $query;
}

// get the post order by portion of the query
function get_orderby_query($orderby)
{
  $query = '';
  if ($orderby == OrderBy::ASC->value) $query = ' ASC';
  if ($orderby == OrderBy::DESC->value) $query = ' DESC';
  return $query;
}
