<?php
include("includes/init.php");
$plants = "class = 'current'";

$db = open_sqlite_db("./secure/plants.sqlite");

$messages = array();

function print_data($data)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($data["_name"]); ?></td>
    <td><?php echo htmlspecialchars($data["benefits"]); ?></td>
    <td><?php echo htmlspecialchars($data["_description"]); ?></td>
    <td><?php echo htmlspecialchars($data["_location"]); ?></td>
    <td><?php echo htmlspecialchars($data["prepare"]); ?></td>
  </tr>
<?php
}

// Search Form

if (isset($_GET['search'])) {
  $do_search = TRUE;
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  $do_search = FALSE;
  $search = NULL;
}

// Insert Form

$plants = exec_sql_query($db, "SELECT DISTINCT _name FROM plants", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // $valid_review = TRUE;

  $_name = filter_input(INPUT_POST, "_name", FILTER_SANITIZE_STRING);
  $benefits = filter_input(INPUT_POST, "benefits", FILTER_SANITIZE_STRING);
  $_description = filter_input(INPUT_POST, "_description", FILTER_SANITIZE_STRING);
  $_location = filter_input(INPUT_POST, "_location", FILTER_SANITIZE_STRING);
  $prepare = filter_input(INPUT_POST, "prepare", FILTER_SANITIZE_STRING);

  // // name and benefits not required
  // if (empty($_name)) {
  //   $valid_review = FALSE;
  // }
  // if (empty($_benefits)) {
  //   $valid_review = FALSE;
  // }


  // insert user input herbs
  // if ($valid_review) {

    $sql= "INSERT INTO plants (_name, benefits, _description, _location, prepare) VALUES (:_name, :benefits, :_description, :_location, :prepare)";
    $params = array(
      ":_name" => $_name,
      ":_description" => $_description,
      ":benefits" => $benefits,
      ":_location" => $_location,
      ":prepare" => $prepare
    );


    $res= exec_sql_query($db, $sql, $params);
    if ( $res ) {
      array_push($messages, "We have added your favorite herb to our collection. Thank you for your input!");
    } else {
      array_push($messages, "We could not add the information to our database. Please try again.");
    }
  // } else {
  //   array_push($messages, "Please make sure to add the name of the herb as well as its benefits and try again.");
  // }
}
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

    <div id= "searchbar">
      <form id="searchForm" action="plants.php" method="get" novalidate>
        <input type="text" name="search" required />
        <button class= button1 type="submit"> Search </button>
      </form>
    </div>
    <?php
    if ($do_search) { ?>
      <h2>Herbs Requested</h2>

      <?php
        // search query
        $sql = "SELECT * FROM plants WHERE _name LIKE '%' || :search || '%' OR benefits LIKE '%' || :search || '%' OR _description LIKE '%' || :search || '%' OR _location LIKE '%' || :search || '%' OR prepare LIKE '%' || :search || '%'";
        $params = array(':search' => $search);

    } else {
      // show all
      ?>
      <div id= messege>
        <?php
        foreach ($messages as $message) {
          echo htmlspecialchars($message) ;}
        ?>
      </div>
      <h2>Our Collection</h2>
      <?php

      $sql = "SELECT * FROM plants";
      $params = array();
    }

    // display the data
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $datas = $result->fetchAll();

      if (count($datas) > 0) {
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
          foreach ($datas as $data) {
            print_data($data);
          }
          ?>
        </table>
    <?php
      } else {
        // No results found
        echo "<p>We do not have any information on that currently. You can check out more herbs here: https://web.extension.illinois.edu/herbs/directory.cfm </p>";
      }
    }
    ?>

    <h2>Let us know about your favorite Herb.</h2>

    <form id="addherb" action="plants.php" method="post" novalidate>
        <div class="form">
          <label>Name:</label>
          <div>
            <input type="text" name="_name" />
          </div>
        </div>
        <div class="form">
          <label>Benefits:</label>
          <div>
            <input type="text" name="benefits" />
          </div>
        </div>
        <div class="form">
          <label>Description:</label>
          <div>
            <input type="text" name="_description" />
          </div>
        </div>
        <div class="form">
          <label>Location:</label>
          <div>
            <input type="text" name="_location" />
          </div>
        </div>
        <div class="form">
          <label>Prepare:</label>
          <div>
            <input type="text" name="prepare" />
          </div>
        </div>
        <div class="form">
          <span></span>
          <button class=button2 type="submit"> Add Herb </button>
        </div>
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
