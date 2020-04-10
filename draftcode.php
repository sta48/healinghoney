<?php
include("includes/init.php");
$plants = "class = 'current'";

$db = open_sqlite_db("./secure/plants.sqlite");

$feedback = array();

function print_data($data)
{
?>
  <tr>
    <td>
      <?php echo htmlspecialchars($data["name"]);?>
    </td>
    <td>
    <?php echo htmlspecialchars($data["benefits"]);?>
    </td>
    <td>
    <?php echo htmlspecialchars($data["description"]);?>
    </td>
    <td>
    <?php echo htmlspecialchars($data["location"]);?>
    </td>
    <td>
    <?php echo htmlspecialchars($data["prepare"]);?>
    </td>
  </tr> -->
<?php }

// Search Form
if (isset($_GET['search'])){
  $conduct_search = TRUE;
  $search = filter_input(INPUT_GET,'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
}else{
  $conduct_search = FALSE;
  $search = NULL;
}

// Insert Form
$plants = exec_sql_query($db, "SELECT _name FROM plants", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_info = TRUE;

  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $benefits = filter_input(INPUT_POST, 'benefits', FILTER_SANITIZE_STRING);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
  $prepare = filter_input(INPUT_POST, 'prepare', FILTER_SANITIZE_STRING);

  // name, benefits, and prepare are required
  if (!in_array($name, $plants)) {
    $valid_info = FALSE;
  }if (!in_array($benefits, $plants)) {
    $valid_info = FALSE;
  }if (!in_array($prepare, $plants)) {
    $valid_info= FALSE;
  }

  if ($valid_info) {

    $sql = "INSERT INTO plants (name, benefits, description, location, prepare) VALUES (:name, :benefits, :description, :location, :prepare)";
    $params = array(
      ":name" => $name,
      ":benefits" => $benefits,
      ":description" => $description,
      ":location" => $location,
      ":prepare" => $prepare
    );

    $res = exec_sql_query($db, $sql, $params);
    if ($res) {
      array_push($feedback, "Your herb has been added to our database. We appreciate your contribution!");
    } else {
      array_push($feedback, "Your information could not be added. Please try again.");
    }
  } else {
    array_push($feedback, "Your information could not be added. Please make sure to add valid name, benefits, and preparation information.");
  }
}
