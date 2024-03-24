<?php

/**
 * Redirect to a url.
 * @param string $url The URL to which you want to redirect.
 */
function redirect($url)
{
  header('Location: ' . $url);
  exit();
}

/**
 * Redirect to a url after specific seconds.
 * @param int $delay The seconds for waiting
 * @param string $url The URL to which you want to redirect.
 */
function redirect_delay($delay, $url)
{
  header('refresh:' . $delay . '; url=' . $url);
  exit();
}
