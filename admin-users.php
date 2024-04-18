<?php
session_start();
// print_r($_SESSION);
require 'utilities.php';
require 'admin-authentication.php';
require 'admin-user-preference.php';

// check permission
if (!(is_logged_in() && has_role([1]))) {
  redirect('index.php');
}

require 'db_connection.php';

// get user setting preference
$admin_preference = get_admin_preference();
$user_sortby_query = get_user_sortby_query($admin_preference['user_sortby']);
$orderby_query = get_orderby_query($admin_preference['orderby']);

$query = "SELECT * FROM users u JOIN roles r ON u.role_id = r.role_id" . $user_sortby_query . $orderby_query;
$statement = $db_conn->prepare($query);
$statement->execute();
$rows = $statement->fetchAll();
$users_count = count($rows);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Center | Users</title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-admin-header.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap row">
      <?php
      include "template-admin-sidebar.php";
      ?>
      <div class="col ms-3">
        <div class="mt-4 d-flex justify-content-between align-items-end">
          <h1>Users</h1>
          <a class="btn btn-green px-5 fw-bold" href="admin-users-form.php?action=add">ADD</a>
        </div>
        <div>
          <div class="row mt-4" style="color:#929AA6;">
            <div class="col-4">Found <span style="color:#DEE0E0;"><?= $users_count ?></span> Users</div>
            <div class="col text-end fs-6">
              <form name="list-preference-form" method="POST" action="admin-user-preference.php">
                <label for="orderby">Sort by</label>
                <select class="custom-dropdown-light dropdown-orderby ms-1" name="user_sortby" id="user_sortby" onchange="this.form.submit()">
                  <?php foreach (UserSortBy::cases() as $option) : ?>
                    <option value="<?= $option->value ?>" <?= $admin_preference['user_sortby'] == $option->value ? 'selected' : '' ?>>
                      <?= $option->value ?></option>
                  <?php endforeach ?>
                </select>
                <select class="custom-dropdown-light dropdown-orderby ms-1 me-4" name="orderby" id="orderby" onchange="this.form.submit()">
                  <?php foreach (OrderBy::cases() as $option) : ?>
                    <option value="<?= $option->value ?>" <?= $admin_preference['orderby'] == $option->value ? 'selected' : '' ?>>
                      <?= $option->value ?></option>
                  <?php endforeach ?>
                </select>
                <select class="custom-dropdown-light dropdown-pagesize me-1" name="pagesize" id="pagesize" onchange="this.form.submit()">
                  <?php foreach (PageSize::cases() as $option) : ?>
                    <option value="<?= $option->value ?>" <?= $admin_preference['pagesize'] == $option->value ? 'selected' : '' ?>>
                      <?= $option->value ?></option>
                  <?php endforeach ?>
                </select>
                <label for="pagesize">per page</label>
              </form>
            </div>
          </div>
          <div class="admin-list-plane mt-3 p-2">
            <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 4rem;">#</th>
                  <th style="width: 13rem;">Username</th>
                  <th>Full Name</th>
                  <th style="width: 15rem;">Role</th>
                  <th style="width: 8rem;">Operation</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row) : ?>
                  <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['role_name'] ?></td>
                    <td>
                      <a class="operate-icon tooltip-view" href="admin-users-form.php?action=view&id=<?= $row['user_id'] ?>"><img src=" images/icon-view.png" alt="View" /></a>
                      <a class="operate-icon tooltip-edit" href="admin-users-form.php?action=edit&id=<?= $row['user_id'] ?>"><img src=" images/icon-edit.png" alt="Edit" /></a>
                      <a class="operate-icon tooltip-delete" href="admin-users-process.php?action=delete&id=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user? Deleting a user will result in the deletion of all posts made by that user.')"><img src=" images/icon-delete.png" alt="Delete" /></a>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  ?>
</body>

</html>