<header class="pt-5 pb-4 d-flex justify-content-center bg-black">
  <div class="wrap d-flex gap-5">
    <div class="logo d-flex align-items-center">
      <a href="index.php"><img src="images/logo.png" alt="logo"></a>
    </div>
    <nav class="navigation me-5 d-flex justify-content-end align-items-center flex-grow-1">
      <ul class="d-flex gap-5">
        <li class=""><a class="navigation-link" href="index.php">NEWS</a></li>
        <li class=""><a class="navigation-link" href="reviews.php">REVIEWS</a></li>
        <li class=""><a class="navigation-link" href="games.php">FIND GAMES</a></li>
        <li class=""><a class="navigation-link" href="rank.php">RANK</a></li>
        <li class=""><a class="navigation-link" href="aboutme.php">ABOUT ME</a></li>
      </ul>
    </nav>
    <?php if (!is_logged_in()) : ?>
      <div class="user-entry me-3 d-flex align-items-center">
        <a href="user.php?action=login">Log In</a><span class="mx-1">/</span><a href="user.php?action=signup">Sign Up</a>
      </div>
    <?php else : ?>
      <div class="user me-3 d-flex align-items-center dropdown">
        <a class="user-avatar d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img class="me-1" src="images/user-avatar.png" alt="user" width="32" height="32">
          <img src="images/icon-expand.png" alt="expand" width="8" height="8"></a>
        <ul class="dropdown-menu bg-black border border-black">
          <li class="pt-4 px-3">
            <div class=""><?= $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] ?></div>
            <div class="text-secondary"><?= $_SESSION['user']['email'] ?></div>
          </li>
          <li>
            <hr class="dropdown-divider bg-dark">
          </li>
          <li><a class="dropdown-item" href="account.php">My Account</a></li>
          <li>
            <hr class="dropdown-divider bg-dark">
          </li>
          <li><a class="dropdown-item text-white" href="user-action.php?action=signout">Sign Out</a></li>
        </ul>
      </div>
    <?php endif ?>
  </div>
</header>