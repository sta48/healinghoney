<?php
include("includes/init.php");
$title = "Herb Collection";

// open connection to database
$db = open_sqlite_db("./secure/plants.sqlite");

// An array to deliver messages to the user.
$messages = array();

function print_record($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["_name"]); ?></td>
    <td><?php echo htmlspecialchars($record["benefits"]); ?></td>
    <td><?php echo htmlspecialchars($record["_description"]); ?></td>
    <td><?php echo htmlspecialchars($record["_location"]); ?></td>
    <td><?php echo htmlspecialchars($record["prepare"]); ?></td>
  </tr>
<?php
}

// Search Form

if (isset($_GET['search'])) {
  $do_search = TRUE;

  // Get the search terms
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  // No search provided, so set the product to query to NULL
  $do_search = FALSE;
  $search = NULL;
}

// Insert Form

// Get the list of shoes from the database.
$plants = exec_sql_query($db, "SELECT DISTINCT _name FROM plants", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_review = TRUE;

  $_name = filter_input(INPUT_POST, '_name', FILTER_SANITIZE_STRING);
  $benefits = filter_input(INPUT_POST, 'benefits', FILTER_SANITIZE_STRING);
  $_description = filter_input(INPUT_POST, '_description', FILTER_SANITIZE_STRING);
  $_location = filter_input(INPUT_POST, '_location', FILTER_SANITIZE_STRING);
  $prepare = filter_input(INPUT_POST, '_prepare', FILTER_SANITIZE_STRING);

  // name and benefits required
  if (!in_array($_name, $plants)) {
    $valid_review = FALSE;
  }
  if (!in_array($_benefits, $plants)) {
    $valid_review = FALSE;
  }

  // reviewer and comment are optional; no filtering necessary

  // insert valid reviews into database
  if ($valid_review) {
    // TODO: query to insert new record

    $sql= "INSERT INTO plants (_name, benefits, _description, _location, prepare) VALUES (:_name, :benefits, :_description, :_location, :prepare)";
    $params = array(
      ":_name" => $name,
      ":_description" => $_description,
      ":benefits" => $benefits,
      ":_location" => $_location,
      ":prepare" => $prepare
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
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width-device-width, initial-scale=1">
    <meta name="description" content="Get the latest information and updates from Healing Honey about naturaly rejuvinating your body and reclaiming your inner energy.">
    <meta name="keywords" content="herbs, healing, plants, medicine, apothecary, homeopathy, natural, remedy, eco-friendly, green">
    <meta name="author" content="Sayeeda Aishee">
    <title>Naturaly heal your body | Healing Honey</title>
    <link rel="stylesheet" href="./css/style.css">
  </head>

<?php include("includes/head.php"); ?>

<body>
  <?php include("includes/nav.php"); ?>

  <main>
    <h2><?php echo $title; ?></h2>
    <p>Welcome to Herb Collection!</p>

    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="plants1.php" method="get" novalidate>
      <input type="text" name="search" required />
      <button type="submit">Search</button>
    </form>

    <?php
    if ($do_search) { // We have a specific shoe to query! ?>
      <h2>Search Results</h2>

      <?php
        // Search across all fields at once!
        // TODO: query to search across all fields at once. Hint: You need logical operators

        $sql = "SELECT * FROM plants WHERE _name LIKE '%' || :search || '%' OR benefits LIKE '%' || :search || '%' OR _description LIKE '%' || :search || '%' OR _location LIKE '%' || :search || '%' OR prepare LIKE '%' || :search || '%'";
        $params = array(':search' => $search);

    } else {
      // No shoe to query, so return everything!
      // Hint: You don't need to change any of this code.
      ?>
      <h2>All Reviews</h2>
      <?php

      $sql = "SELECT * FROM plants";
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
            <th>Name</th>
            <th>Benefits</th>
            <th>Description</th>
            <th>Location</th>
            <th>Prepare</th>
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
        <label>Name</label>
        <input type="text" name="_name" />
      </div>
      <div class="group_label_input">
        <label>Benefits</label>
        <input type="text" name="_name" />
      </div>
      <div class="group_label_input">
        <label>Description</label>
        <input type="text" name="_name" />
      </div>
      <div class="group_label_input">
        <label>Location</label>
        <input type="text" name="_name" />
      </div>
      <div class="group_label_input">
        <label>Prepare</label>
        <input type="text" name="_name" />
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
