<?php
    require dirname(__FILE__,2).'/vendor/autoload.php';

    include dirname(__FILE__,2).'/connect.php';

    include dirname(__FILE__,2).'/helper_files/GeneralFunctions.php';

    include dirname(__FILE__,2).'/model/Department.php';
    
    // Uncomment for localhost running
    // $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
    // $dotenv->load();
    
    $MDB_USER = $_ENV['MDB_USER'];
    $MDB_PASS = $_ENV['MDB_PASS'];
    $ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];
    
    function saveDepartment($data){
        global $department;

        $datatosave = json_decode(json_encode($data));
        $result = $department->createDepartment($datatosave);
        return $result;
    }

    function deleteDepartment($id){
        global $department;

        $result = $department->deleteDepartment($id);
        return $result;
    }

    function updateDepartment($data){
        global $department;

        $datatosave = json_decode(json_encode($data));
        $result = $department->updatecDepartment($datatosave);
        return $result;
    }

    $connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);
    $department = new Department($connection);
    header('Content-Type: text/html; charset:UTF-8');

    $nameErr = $identifierErr ="";
    $name = $identifier = "";

    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
        if (empty($_POST["id"])){
            $update = false;
            echo "XXXX>". $update. " >>>> ".$_POST["id"];
        } else { 
            $update = true;
            echo "XXXX>". $update. " >>>> ".$_POST["id"];
        }
        

        if (empty($_POST["name"])){
            $nameErr = "Name is required";
        } else {
            if (!preg_match("/^[a-zA-Z\p{Greek}\s]+$/u",$_POST["name"]))
                $nameErr = "Invalid format for field name";
        }
        
        if (empty($_POST["identifier"])){
            $identifierErr = "Identifier is required";
        } else {
            if (!is_numeric($_POST["identifier"]))
                $identifierErr = "Invalid is not number";
        }

        if (empty($nameErr) && empty($identifierErr)){
            $data = array(
                'identifier' => $_POST["identifier"],
                'name' => $_POST["name"]
            );
            
            if ($update) { 
                $data = array(
                    '_id' => $_POST["id"],
                    'identifier' => $_POST["identifier"],
                    'name' => $_POST["name"]
                );
                $result = updateDepartment($data);    
            }
            else {
                $data = array(
                    'identifier' => $_POST["identifier"],
                    'name' => $_POST["name"]
                );
                $result = saveDepartment($data);
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"]=="GET"){
        
        if (isset($_GET["id"]) && !empty($_GET["id"])) {
            $id = $_GET["id"];
            $result = deleteDepartment($id);
        }
    }
  
    $showResults = json_decode($department->showDepartments(),true);
    $showResults = json_decode($showResults['data'],true);
    // print_r($data);
?>

<html>
    <head>
        <title>Departments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
            .error{
                color:red;
            }
            
            table, th, td{
                border: 1px solid; 
            }
        </style>
    </head>

    <body>
        <h1>Εισαγωγή νέας διεύθυνσης</h2>

        <p><span class="error">* required field</span></p>
        
        <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Identifier: <input type="text" name="identifier" id="identifier" value="<?php echo $identifier; ?>">
            <span class="error">* <?php echo $identifierErr; ?></span>
            <br><br>
            Name: <input type="text" name="name" value="<?php echo $name; ?>" id="name">
            <span class="error">* <?php echo $nameErr; ?></span>
            <br><br>
            <input type="hidden" name="id" id="id" value="">
            <input type="submit" name="Submit" value="Submit">
        </form>
        
        <hr>
        
        <table>
            <tr>
                <th>Διεύθυνση</th>
                <th>Αναγνωριστικό</th>
                <th>Τμήματα</th>
                <th>Κατηγορίες</th>
                <th>Διαδικασίες</th>
            </tr>
            <?php
                foreach ($showResults as $value){
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
                                echo $valueX["name"]."<br>";
                            }
                        echo "</td>";
                        echo "<td>";
            ?>
                            <button onclick="loadform(<?php echo '\''.$value['_id']['$oid'].'\',\''.$value['name'].'\',\''.$value['identifier'].'\''?>)">Update</button>
                            <form method="delete" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                <input type="hidden" name="id" value="<?php echo $value['_id']['$oid']; ?>">
                                <input type="submit" name="submit" value="Delete">
                            </form>
            <?php
                    echo "</td>";
                    echo "</tr>";    
                }
            ?>
        </table>
        <script>
            function loadform(id,name,identifier){
                // console.log("xxxxxxx",id,name,identifier);
                $('#name').val(name);
                $('#identifier').val(identifier);
                $('#id').val(id);
            }
        </script>

    </body>
</html>