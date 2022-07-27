<?php 
require dirname(__FILE__,2).'/vendor/autoload.php';

include dirname(__FILE__,2).'/connect.php';

// Uncomment for localhost running
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

$MDB_USER = $_ENV['MDB_USER'];
$MDB_PASS = $_ENV['MDB_PASS'];
$ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

$connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);

$collection = $connection->connect_to_department();
$data = $collection->find()->toArray();

// echo dirname(__FILE__,2)."<br>";
// echo dirname(__FILE__)."<br>";
// echo __DIR__."<br>";
// echo "<br>";
// print_r($data);
?>

<html>
    <head>
        <title>Departments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>

    <body>

        <table border="1px">
            <tr>
                <th>Διεύθυνση</th>
                <th>Αναγνωριστικό</th>
                <th>Τμήματα</th>
                <th>Κατηγορίες</th>
            </tr>
            <?php
                foreach ($data as $value){
                    echo "<tr>";
                        echo "<td>".$value['name']."</td>";
                        echo "<td>".$value['identifier']."</td>";
                        echo "<td>";
                            foreach ($value["subdepartment"] as $valueX){
                                echo $valueX["name"]."<br>";
                            }
                        echo "</td>";
                        echo "<td>";
                            foreach ($value["categories"] as $valueX){
                                echo $valueX["name"]."(".$valueX["subdepartment_id"].")<br>";
                            }
                        echo "</td>";
                    echo "</tr>";    
                }
            ?>
        </table>


    </body>
</html>