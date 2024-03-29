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

/**
 * Limit the numbers of words in a content
 * @param string $content The content needs to be limited in length.
 * @param int $limit Words to be displayed.
 * @return string Return a trimmed content.
 */
function limit_words($content, $limit)
{
  $words = explode(' ', $content);
  if (count($words) > $limit) {
    $words = array_slice($words, 0, $limit);
    $limited_content = implode(' ', $words);
    return $limited_content . '...';
  }
  return $content;
}
