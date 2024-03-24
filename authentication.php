<?php

/**
 * check if the user is logged in
 * @return bool Return true if approved.
 */
function is_logged_in()
{
  return isset($_SESSION['user']['user_id']);
}

/**
 * check if the user has permission to access the features
 * @param array $auth_roles An array includes what roles can access the features
 * @return bool Return true if approved.
 */
function has_role($auth_roles)
{
  if (!isset($_SESSION['user']['role_id'])) {
    return false;
  }

  return in_array($_SESSION['user']['role_id'], $auth_roles);
}
