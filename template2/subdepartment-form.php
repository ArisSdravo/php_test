<<?php include 'header-script.php'; ?>

<?php
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function saveSubdepartment($data){
        global $subdepartment;
        
        $data = json_decode(json_encode($data));
        $result = $subdepartment->createSubdepartment($data);
        return $result;
    }

    // // define variables and set to empty values
    $nameErr = $identifierErr = "";
    $name = $identifier = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // echo $_POST["name"].",".$_POST["identifier"];
         
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace or Greek letters
            if (!preg_match("/^[a-zA-Z\p{Greek}\s]+$/u",$name)) {
            $nameErr = "Only letters and white space allowed";
            }
        }
         
        if (empty($_POST["identifier"])) {
            $identifierErr = "Identifier is required";
        } else {
            $identifier = test_input($_POST["identifier"]);
            // check if identifier is number
            if (!is_numeric($identifier)) {
            $identifierErr = "Invalid identifier format";
            }
        }

        if (empty($identifierErr) && empty($nameErr)){
            $data = array(
                'identifier' => $identifier,
                'name' => $name
            );
            $result = saveSubdepartment($data);
        }
    }

    $allDepartments = json_decode($department->showDepartments(),true);
    $allDepartments = json_decode($allDepartments['data'],true);
?>

<?php include 'header.php'; ?>
    <div class="container mt-4">   

        <h2>Εισαγωγή νέου Subdepartment</h2>
        <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
        <p><span class="text-danger">* required field</span></p>
           
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3">
                <label for="identifier" class="form-label">Identifier</label>
                <select name="identifier" id="identifier">
                <option value="" default>Επιλέξτε Διεύθυνση</option>
                <?php 
                    foreach($allDepartments as $value) {
                        echo '<option value="'.$value['identifier'].'">'.$value['name']."</option>";
                    } 
                ?>
            </select>
            <span class="text-danger">* <?php echo $identifierErr;?></span>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>">
                <span class="text-danger">* <?php echo $nameErr;?></span>
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
                foreach($allDepartments as $value) {
                    echo '<tr>';
                        echo '<td>'.$value['name'].'</td>';
                        echo '<td>'.$value['identifier'].'</td>';
                        echo '<td>';
                            foreach ($value['subdepartment'] as $svalue){
                                echo $svalue['name']."<br>";
                            }
                        echo '</td>';
                        echo '<td>';
                            foreach ($value['categories'] as $cvalue){
                                echo $cvalue['name']."<br>";
                            }
                        echo '</td>';
                    echo '</tr>';
                }
            ?>
        </table>

    </div>
<?php include 'footer.php'; ?>