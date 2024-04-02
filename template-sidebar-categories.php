<?php
// retrieve categories
$categories_query = "SELECT * FROM categories";
try {
  $categories_statement = $db_conn->prepare($categories_query);
  $categories_statement->execute();
  $category_rows = $categories_statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('There is an error when retrieving categories.');
}
?>
<?php if (!empty($category_rows)) : ?>
  <div class="category_sidebar mt-3">
    <h2>Post Categories</h2>
    <ul>
      <?php foreach ($category_rows as $category_row) : ?>
        <li><a href="search.php?cid=<?= $category_row['category_id'] ?>"><?= $category_row['category_name'] ?></a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif ?>