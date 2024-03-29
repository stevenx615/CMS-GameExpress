<header class="pt-5 pb-4 d-flex justify-content-center bg-black">
  <div class="wrap d-flex gap-5 justify-content-between">
    <div class="logo d-flex align-items-center">
      <a href="index.php"><img src="images/logo.png" alt="logo"></a>
    </div>
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
  </div>
</header>