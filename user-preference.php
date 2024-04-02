<?php
require_once 'utilities.php';

enum PostSortBy: string
{
  case TITLE = 'Title';
  case CREATED_DATE = 'Created Date';
  case MODIFIED_DATE = 'Modified Date';
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
    if (
      isset($_POST['orderby'])
      && isset($_POST['pagesize'])
      && isset($_POST['post_sortby'])
    ) {
      $preference = array(
        'orderby' => $_POST['orderby'],
        'pagesize' => $_POST['pagesize'],
        'post_sortby' => $_POST['post_sortby'],
      );
      setcookie('preference', json_encode($preference), time() + 3600 * 24, "/");
      redirect(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
    }
  }
}


function check_preference(&$preference, $default_preference, $key)
{
  if (empty($preference[$key])) {
    $preference[$key] = $default_preference[$key];
    setcookie('preference', json_encode($preference), time() + 3600 * 24, "/");
    redirect($_SERVER['REQUEST_URI']);
  }
}

function get_preference()
{
  $preference = array();

  $default_preference = array(
    'orderby' => OrderBy::DESC->value,
    'pagesize' => PageSize::SIZE_1->value,
    'post_sortby' => PostSortBy::CREATED_DATE->value,
  );

  // get the preference from cookie, if empty then reset to default preference
  if (!empty($_COOKIE['preference'])) {
    $preference = json_decode($_COOKIE['preference'], true);
    check_preference($preference, $default_preference, 'orderby');
    check_preference($preference, $default_preference, 'pagesize');
    check_preference($preference, $default_preference, 'post_sortby');
  } else {
    $preference = $default_preference;
    setcookie('preference', json_encode($preference), time() + 3600 * 24);
    redirect($_SERVER['REQUEST_URI']);
  }
  return $preference;
}

// get the post sort by portion of the query
function get_post_sortby_query($sortby)
{
  $query = '';
  if ($sortby == PostSortBy::TITLE->value) $query = 'p.post_title';
  if ($sortby == PostSortBy::CREATED_DATE->value) $query = 'p.post_created_date';
  if ($sortby == PostSortBy::MODIFIED_DATE->value) $query = 'p.post_modified_date';
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
