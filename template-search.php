<div class="search-section py-3 d-flex justify-content-center">
  <div class="wrap d-flex justify-content-between">
    <?php if (is_logged_in() && has_role([1, 2])) : ?>
      <div>
        <a class="btn btn-green px-5" href="admin-posts.php">Admin Center</a>
      </div>
    <?php endif ?>
    <?php
    $search_categories_query = "SELECT * FROM categories";
    $categories_stmt = $db_conn->prepare($search_categories_query);
    $categories_stmt->execute();
    $search_category_rows = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="flex-grow-1 text-end">
      <form class=" search-form" method="post" action="search.php?action=search">
        <select name="category_selected" id="category_selected" class="custom-dropdown">
          <option value="-1" selected>All Categories</option>
          <?php foreach ($search_category_rows as $search_category_row) : ?>
            <option value="<?= $search_category_row['category_id'] ?>"><?= $search_category_row['category_name'] ?></option>
          <?php endforeach ?>
        </select>
        <select name="field_selected" id="field_selected" class="custom-dropdown">
          <option value="title" selected>Title</option>
          <option value="author">Author</option>
          <option value="content">Content</option>
        </select>
        <div class="search-input d-inline">
          <input type="text" placeholder="Search for articles..." class="search-input-main" name="search_content">
          <button class="btn-search-main" type="submit"></button>
        </div>
      </form>
    </div>
  </div>
</div>