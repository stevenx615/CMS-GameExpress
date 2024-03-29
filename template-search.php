<div class="search-section py-3 d-flex justify-content-center">
  <div class="wrap d-flex justify-content-between">
    <?php if (is_logged_in() && has_role([1])) : ?>
      <div>
        <a class="btn btn-green px-5" href="admin-posts.php">Admin Center</a>
      </div>
    <?php endif ?>
    <div class="flex-grow-1 text-end">
      <form class=" search-form" method="post" action="">
        <select name="categories" id="categories" class="custom-dropdown">
          <option value="all" selected>All</option>
          <option value="1">News</option>
          <option value="2">Reviews</option>
        </select>
        <div class="search-input d-inline">
          <input type="text" placeholder="Search for articles..." class="search-input-main">
          <button class="btn-search-main" type="submit"></button>
        </div>
      </form>
    </div>
  </div>
</div>