<?php
require_once 'utilities.php';

enum OrderBy: string
{
  case ASC = 'asc';
  case DESC = 'desc';
}

enum PageSize: int
{
  case SIZE_1 = 10;
  case SIZE_2 = 20;
  case SIZE_3 = 50;
  case SIZE_4 = 100;
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
  );

  // get the preference from cookie, if empty then reset to default preference
  if (!empty($_COOKIE['admin-preference'])) {
    $preference = json_decode($_COOKIE['admin-preference'], true);
    check_preference($preference, $default_preference, 'orderby');
    check_preference($preference, $default_preference, 'pagesize');
  } else {
    $preference = $default_preference;
    setcookie('admin-preference', json_encode($preference), time() + 3600 * 24);
    redirect($_SERVER['REQUEST_URI']);
  }
  return $preference;
}
