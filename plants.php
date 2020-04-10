<?php
include("includes/init.php");
$plants = "class = 'current'";

// open connection to database
$db = open_sqlite_db("secure/plants.sqlite");

// An array to deliver messages to the user.
$messages = array();

function print_record($record)
{
?>
  <tr>
    <td>
      <?php echo htmlspecialchars($record["name"]); ?>
    </td>
    <td>
      <?php echo htmlspecialchars($record["benefits"]);?>
    </td>
    <td>
      <?php echo htmlspecialchars($record["description"]); ?>
    </td>
    <td>
      <?php echo htmlspecialchars($record["location"]); ?>
    </td>
    <td>
      <?php echo htmlspecialchars($record["prepare"]); ?>
    </td>
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
// $plants = exec_sql_query($db, "SELECT DISTINCT _name FROM plants", NULL)->fetchAll(PDO::FETCH_COLUMN);

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//   $valid_review = TRUE;

//   $_name = filter_input(INPUT_POST, '_name', FILTER_SANITIZE_STRING);
//   $benefits = filter_input(INPUT_POST, 'benefits', FILTER_SANITIZE_STRING);
//   $_description = filter_input(INPUT_POST, '_description', FILTER_SANITIZE_STRING);
//   $_location = filter_input(INPUT_POST, '_location', FILTER_VALIDATE_EMAIL);
//   $prepare = filter_input(INPUT_POST, 'prepare', FILTER_SANITIZE_STRING);

//   // product name required
//   if (!in_array($_name, $shoes)) {
//     $valid_review = FALSE;
//   }
//   if (!in_array($_description, $shoes)) {
//     $valid_review = FALSE;
//   }
//   if (!in_array($benefits, $shoes)) {
//     $valid_review = FALSE;
//   }

//   // reviewer and comment are optional; no filtering necessary

//   // insert valid reviews into database
//   if ($valid_review) {
//     // TODO: query to insert new record

//     $sql= "INSERT INTO plants (_name, benefits, _description, _location, prepare) VALUES (:_name, :benefits :_description :_location, :prepare)";
//     $params = array(
//       ":_name" => $_name,
//       ":_description" => $_description,
//       ":benefits" => $benefits,
//       ":_location" => $location,
//       ":prepare" => $prepare
//     );


//     $res= exec_sql_query($db, $sql, $params);
//     /* TODO: conditional expression to check if query was successful.*/
//     if ( $res ) {
//       array_push($messages, "Your herb has been added to our database. We appreciate your contribution!");
//     } else {
//       array_push($messages, "our information could not be added. Please try again.");
//     }
//   } else {
//     array_push($messages, "Your information could not be added. Please make sure to add valid name, benefits, and preparation information.");
//   }
// }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width-device-width, initial-scale=1">
    <meta name="description" content="Get the latest information and updates from Healing Honey about naturaly rejuvinating your body and reclaiming your inner energy.">
    <meta name="keywords" content="herbs, healing, plants, medicine, apothecary, homeopathy, natural, remedy, eco-friendly, green">
    <meta name="author" content="Sayeeda Aishee">
    <title>Naturaly heal your body | Healing Honey</title>
    <link rel="stylesheet" href="./css/style.css">
  </head>

  <body>
    <?php include './includes/nav.php';?>

    <section id=collage>
      <div class="container1">
        <!-- Source: https://ncfarmsinc.com/unrooted-cuttings/Herb-Salvia-Officinalis-Purple-Sage, https://fineartamerica.com/featured/1-maidenhair-tree-ginkgo-biloba-brian-gadsbyscience-photo-library.html, https://www.epicurious.com/ingredients/fresh-herbs-how-to-use-them-article,   -->
        <img src= "./img/collage.png" alt= "A collage of Herbs">
      </div>
    </section>

    <form id="searchbar" action="plants.php" method="get" novalidate>
      <input type="text" name="search" required/>
      <button type="submit"> Find It </button>
    </form>

    <?php
    if ($do_search) { ?>
      <h2> Herb Collection </h2>

      <?php
      $sql = "SELECT * FROM plants WHERE name LIKE '%' || :search || '%' OR benefits LIKE '%' || :search || '%' OR description LIKE '%' || :search || '%' OR location LIKE '%' || :search || '%' OR prepare LIKE '%' || :search || '%'";

      $params = array(
        ":search" => $search
      );
    } else {
      ?>
      <h2> Our Full Herb Collection </h2>
      <?php

      $sql = "SELECT * FROM plants";
      $params = array();
    }

    $res = exec_sql_query($db, $sql, $params);
    if ($res) {
      $data = $res -> fetchAll();
      if (count($data) > 0){
        ?>
        <table>
          <tr>
            <th> Name </th>
            <th>Benefits</th>
            <th>Description</th>
            <th>Location</th>
            <th>Preparation Methods</th>
          </tr>

          <?php
          foreach ($data as $data) {
            print_data($data);
          }
          ?>
        </table>
    <?php
      } else {
        echo "<p> Sorry we do not have that information yet.</p>";
      }
    }
    ?>

    <?php
    // Feedback
    foreach ($feedback as $feedback) {
      echo "<p><strong>" . htmlspecialchars($feedback) . "</strong></p>\n";
    }
    ?>

    <h2>Add Your Own Favorite Herb</h2>

    <form id = "addherb" action = "plants.php" method = "post" novalidate>
      <div class = "input">
        <label>
          Name:
        </label>
        <input type="Text" name="name" />
      </div>
      <div class = "input">
        <label>
          Benefits:
        </label>
        <input type="Text" name="benefits" />
      </div>
      <div class = "input">
        <label>
          Description:
        </label>
        <input type="Text" name="description" />
      </div>
      <div class = "input">
        <label>
          Location:
        </label>
        <input type="Text" name="location" />
      </div>
      <div class = "input">
        <label>
          Preparation Methods:
        </label>
        <input type="Text" name="prepare" />
      </div>
      <div class = "input">
        <span></span>
        <button type="submit"> Add Herb </button>
      </div>

    </form>

    <footer>
      <?php include './includes/footer.php';?>
      Photo Sources: <cite>
          <a href="https://theplantcircle.com/">The Plant Circle,</a>
          <a href="https://fineartamerica.com/featured/1-maidenhair-tree-ginkgo-biloba-brian-gadsbyscience-photo-library.html">Fineartamerica,</a>
          <a href="https://www.epicurious.com/ingredients/fresh-herbs-how-to-use-them-articlea">Epcurious,</a>
          <a href="https://ncfarmsinc.com/unrooted-cuttings/Herb-Salvia-Officinalis-Purple-Sage">North Carolina Farms,</a>
          <a href="https://www.dailymail.co.uk/health/article-5517557/Lavender-tea-tree-oil-men-MOOBS.html">DailyMail</a>
      </cite>
    </footer>
  </body>
</html>
