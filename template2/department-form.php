<?php include 'header-script.php'; ?>

<?php
    function saveDepartment($data){
        global $department;

        $datatosave = json_decode(json_encode($data));
        $result = $department->createDepartment($datatosave);
        return $result;
    }

    $nameErr = $identifierErr ="";
    $name = $identifier = "";

    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
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
            $result = saveDepartment($data);
        }
    }
  
    $showResults = json_decode($department->showDepartments(),true);
    $showResults = json_decode($showResults['data'],true);
    // print_r($data);
?>

<?php include 'header.php'; ?>
    <div class="container mt-4">
        <h2>Εισαγωγή νέας διεύθυνσης</h2>

        <p><span class="text-danger">* required field</span></p>
        
        <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
        
       

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3">
                <label for="identifier" class="form-label">Identifier</label>
                <input type="text" class="form-control" id="identifier" name="identifier" aria-describedby="emailHelp" value="<?php echo $identifier; ?>">
                <span class="text-danger">* <?php echo $identifierErr; ?></span>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                <span class="text-danger">* <?php echo $nameErr; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        
        <hr>
        
        <table class="table table-striped">
            <tr>
                <th>Διεύθυνση</th>
                <th>Αναγνωριστικό</th>
                <th>Τμήματα</th>
                <th>Κατηγορίες</th>
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
                    echo "</tr>";    
                }
            ?>
        </table>
    </div>
<?php include 'footer.php'; ?>