<?php

require  dirname(__FILE__,2).'/vendor/autoload.php';

include dirname(__FILE__,2).'/connect.php';

include dirname(__FILE__,2).'/helper_files/GeneralFunctions.php';

include dirname(__FILE__,2).'/model/Department.php';


$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();


$MDB_USER = $_ENV['MDB_USER'];
$MDB_PASS = $_ENV['MDB_PASS'];
$ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

function saveDepartment($data) {
    global $department;

    $datatosave = json_decode(json_encode($data));
    $result = $department->createDepartment($datatosave);
    return $result;
}

$connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);

$department = new Department($connection);

$data = json_decode($department->showDepartments(), true);

$data = json_decode($data['data'], true);
header('Content-Type: text/html; charset:UTF-8');

// echo dirname(__FILE__,2)."<br>";
// echo dirname(__FILE__);
// echo __DIR__;

$nameErr = $identifierErr = "";
$name = $identifier = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo "YES POST METHOD";

    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        if (!preg_match("/^[a-zA-Z\p{Greek}\s]+$/u",$_POST["name"])) {
            $nameErr = "Invalid format for field name";
        }
    }
    if (empty($_POST["identifier"])) {
        $identifierErr = "Identifier is required";
    } else {
        if (!is_numeric($_POST["identifier"])) {
            $identifierErr = "Identifier is not a number";
        }
    }

    if (empty($nameErr) && empty($identifierErr)) {
        $data = array(
            'identifier' => $_POST["identifier"],
            'name' => $_POST["name"]
        );
        $result = saveDepartment($data);
    }
}
//  else {
//     echo "YES ANOTHER METHOD: ".$_SERVER['REQUEST_METHOD'];
// }


//print_r($data);

?>

<html>
    <head>
        <title>Departments</title>
        <meta http-equiv="Content-Type" content="text/html; charser=UTF-8"/>
        <style>
            .error {
                color: red;                
            }
            .table, th, td{
                border: 1px solid;
            }
        </style>
    </head>

    <body>

        <h2> Εισαγωγή νέας διεύθυνσης</h2>

        <p><span class="error">* required field</span></p>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Identifier: <input type="text" name="identifier" value="<?php echo $identifier; ?>">
            <span class="error">* <?php echo $identifierErr; ?></span>
            Name: <input type="text" name="name" value="<?php echo $name; ?>"> 
            <span class="error">* <?php echo $nameErr; ?></span>
            <br><br>
            <input type="submit" name="Submit" value="Submit">

        </form>
        <hr>

    <table>
        <tr>
            <th>Διεύθυνση</th>
            <th>Αναγνωριστικό></th>
            <th>Τμηματα</th>
            <th>Κατηγορίες</th>
        </tr>
        <?php
            foreach ($data as $value) {
                echo "<tr>";
                    echo "<td>".$value['name']."</td>";
                    echo "<td>".$value['identifier']."</td>";
                    echo "<td>";
                        foreach ($value["subdepartment"] as $valueX) {
                            echo $valueX["name"]."<br>";
                        }
                    echo "</td>";
                    echo "<td>";
                        foreach ($value["categories"] as $valueX) {
                            echo $valueX["name"]."<br>";
                        }
                    echo "</td>";
                echo "</tr>";
            }
        ?>

    </table>



    </body>
</html>