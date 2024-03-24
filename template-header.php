<header class="pt-5 pb-4 d-flex justify-content-center bg-black">
  <div class="wrap d-flex grow gap-5">
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
    <div class="user-entry me-3 d-flex align-items-center">
      <a href="user.php?action=login">Log In</a><span class="mx-1">/</span><a href="user.php?action=signup">Sign Up</a>
    </div>
    <?php if ($has_login) : ?>
      <div class="user me-3 d-flex align-items-center dropdown">
        <a class="d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img class="me-1" src="images/user-avatar.png" alt="user" width="32" height="32">
          <img src="images/icon-expand.png" alt="expand" width="8" height="8"></a>
        <ul class="dropdown-menu bg-black border border-black">
          <li>
            <div class="mt-3 ms-3">Steven Xu</div>
            <div class="ms-3 text-secondary">xusitenh@gmail.com</div>
          </li>
          <li>
            <hr class=" dropdown-divider bg-dark">
          </li>
          <li><a class="dropdown-item" href="#">My Account</a></li>
          <li>
            <hr class="dropdown-divider bg-dark">
          </li>
          <li><a class="dropdown-item text-white" href="#">Sign Out</a></li>
        </ul>
      </div>
    <?php endif ?>
  </div>
</header>