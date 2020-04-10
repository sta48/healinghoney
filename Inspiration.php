<?php
include("includes/init.php");
$title = "Shoe Review";

// open connection to database
$db = open_sqlite_db("secure/shoes.sqlite");

// An array to deliver messages to the user.
$messages = array();

function print_record($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["reviewer"]); ?></td>
    <td>
      <?php
      $stars = intval($record["rating"]);
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $stars) {
          echo "★";
        } else {
          echo "☆";
        }
      }
      ?>
    </td>
    <td><?php echo htmlspecialchars($record["product_name"]); ?></td>
    <td><?php echo htmlspecialchars($record["comment"]); ?></td>
  </tr>
<?php
}

// Search Form

const SEARCH_FIELDS = [
  "all" => "Search Everything",
  "reviewer" => "Search Reviewers",
  "rating" => "Search Ratings",
  "product_name" => "Search Products",
  "comment" => "Search Comments"
];

if (isset($_GET['search'])) {
  $do_search = TRUE;

  // check if the category exists in the SEARCH_FIELDS array
  // This "filter input" protects us from SQL injection for fields
  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    array_push($messages, "Invalid category for search.");
    $do_search = FALSE;
  }

  // Get the search terms
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  // No search provided, so set the product to query to NULL
  $do_search = FALSE;
  $category = NULL;
  $search = NULL;
}

// Insert Form

// Get the list of shoes from the database.
$shoes = exec_sql_query($db, "SELECT DISTINCT product_name FROM reviews", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_review = TRUE;

  $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);
  $reviewer = filter_input(INPUT_POST, 'reviewer', FILTER_VALIDATE_EMAIL);
  $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
  $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

  // rating required
  if ($rating < 1 || $rating > 5) {
    $valid_review = FALSE;
  }

  // product name required
  if (!in_array($product_name, $shoes)) {
    $valid_review = FALSE;
  }

  // reviewer and comment are optional; no filtering necessary

  // insert valid reviews into database
  if ($valid_review) {
    // TODO: query to insert new record

    $sql= "INSERT INTO reviews (reviewer, rating, product_name, comment) VALUES (:reviewer, :rating, :product_name, :comment)";
    $params = array(
      ":reviewer" => $reviewer,
      ":rating" => $rating,
      ":product_name" => $product_name,
      ":comment" => $comment
    );


    $res= exec_sql_query($db, $sql, $params);
    /* TODO: conditional expression to check if query was successful.*/
    if ( $res ) {
      array_push($messages, "Your review has been recorded. Thank you!");
    } else {
      array_push($messages, "Failed to add review.");
    }
  } else {
    array_push($messages, "Failed to add review. Invalid product or rating.");
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include("includes/head.php"); ?>

<body>
  <?php include("includes/header.php"); ?>

  <main>
    <h2><?php echo $title; ?></h2>
    <p>Welcome to the 2300 Shoe Review!</p>

    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="shoes.php" method="get" novalidate>
      <select name="category">
        <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
          <option value="<?php echo $field_name; ?>"><?php echo $label; ?></option>
        <?php } ?>
      </select>
      <input type="text" name="search" required />
      <button type="submit">Search</button>
    </form>

    <?php
    if ($do_search) { // We have a specific shoe to query! ?>
      <h2>Search Results</h2>

      <?php
      if ($search_field == "all") {
        // Search across all fields at once!
        // TODO: query to search across all fields at once. Hint: You need logical operators

        // $sql = "SELECT * FROM reviews WHERE product_name LIKE '%' || :search || '%' OR rating LIKE '%' || :search || '%' OR reviewer LIKE '%' || :search || '%' OR comment LIKE '%' || :search || '%'";
        // $params = array(':search' => $search);

        $cond_exprs = array();
        foreach(SEARCH_FIELDS as $field => $label){
          if($field!= "all" ){
            array_push($cond_exprs, "(".$field." LIKE '%' || :search || '%')");
          }
        }

        $sql = "SELECT * FROM reviews WHERE " . implode($cond_exprs, " OR ");
        $params = array(
          ':search' => $search
        );
      } else {
        // Search across the specified field
        // TODO: query to search the $search_field ONLY for $search

        $sql = "SELECT * FROM reviews WHERE ". $search_field ." LIKE '%'||:search||'%'";
        $params = array(":search" => $search);
      }
    } else {
      // No shoe to query, so return everything!
      // Hint: You don't need to change any of this code.
      ?>
      <h2>All Reviews</h2>
      <?php

      $sql = "SELECT * FROM reviews";
      $params = array();
    }

    // Get the shoes to display
    // Hint: You don't need to change any of this code.
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      // The query was successful, let's get the records.
      $records = $result->fetchAll();

      if (count($records) > 0) {
        // We have records to display
      ?>
        <table>
          <tr>
            <th>Reviewer</th>
            <th>Rating</th>
            <th>Product</th>
            <th>Comments</th>
          </tr>

          <?php
          foreach ($records as $record) {
            print_record($record);
          }
          ?>
        </table>
    <?php
      } else {
        // No results found
        echo "<p>No matching reviews found.</p>";
      }
    }
    ?>

    <h2>Review a Shoe</h2>

    <form id="reviewShoe" action="shoes.php" method="post" novalidate>
      <div class="group_label_input">
        <label>Email:</label>
        <input type="email" name="reviewer" />
      </div>

      <div class="group_label_input">
        <label>Rating:</label>
        <div>
          <input id="rating5" type="radio" name="rating" value="5" checked /><label for="rating5">5</label>
          <input id="rating4" type="radio" name="rating" value="4" /><label for="rating4">4</label>
          <input id="rating3" type="radio" name="rating" value="3" /><label for="rating3">3</label>
          <input id="rating2" type="radio" name="rating" value="2" /><label for="rating2">2</label>
          <input id="rating1" type="radio" name="rating" value="1" /><label for="rating1">1</label>
        </div>
      </div>

      <div class="group_label_input">
        <label>Product Name:</label>
        <select name="product_name" required>
          <option value="" selected disabled>Choose Shoe</option>
          <?php
          foreach ($shoes as $shoe) {
            echo "<option value=\"" . htmlspecialchars($shoe) . "\">" . htmlspecialchars($shoe) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="group_label_input">
        <label>Comment:</label>
        <textarea name="comment" cols="40" rows="5"></textarea>
      </div>

      <div class="group_label_input">
        <span>
          <!-- empty element; used to align submit button --></span>
        <button type="submit">Add Review</button>
      </div>
      </li>
      </ul>
    </form>

  </main>

  <?php include("includes/footer.php"); ?>
</body>

</html>
