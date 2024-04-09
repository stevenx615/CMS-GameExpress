<?php
// retrieve categories
$cats_sidebar_query = "SELECT * FROM categories";
try {
  $cates_stmt = $db_conn->prepare($cats_sidebar_query);
  $cates_stmt->execute();
  $category_rows = $cates_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('There is an error when retrieving categories.');
}
?>
<?php if (!empty($category_rows)) : ?>
  <div class="category_sidebar mt-3">
    <h2>Post Categories</h2>
    <ul>
      <?php foreach ($category_rows as $search_category_row) : ?>
        <li><a href="search.php?cid=<?= $search_category_row['category_id'] ?>"><?= $search_category_row['category_name'] ?></a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif ?>