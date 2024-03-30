<div class="col-2 admin-sidebar">
  <nav class="admin-nav">
    <ul>
      <li><a href="admin-posts.php">Posts</a></li>
      <?php if (is_logged_in() && has_role([1])) : ?>
        <li><a href="admin-categories.php">Categories</a></li>
        <li><a href="admin-games.php">Games</a></li>
        <li><a href="admin-game-categories.php">Game Categories</a></li>
        <li><a href="admin-users.php">Users</a></li>
      <?php endif ?>
    </ul>
  </nav>
</div>