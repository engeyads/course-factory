<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    
    $id = isset($_GET['id']) ? $_GET['id'] : false;
    if($id){
        $where = "WHERE users.id = $id";

        // Step 1: Retrieve available values from the "db" table
        $availableValues = []; // Array to store available values
        $query = "SELECT * FROM db";
        $result = mysqli_query($conn, $query); // Replace $connection with your database connection variable

        while ($row = mysqli_fetch_assoc($result)) {
            $availableValues[$row['id']] = $row['name']; // Assuming 'id' is the unique identifier column and 'name' is the value you want to display
        }

        // Step 2: Retrieve selected values for the user from the "database_user" table
        $selectedValues = []; // Array to store selected values

        $query = "SELECT * FROM user_db WHERE user_id = $id"; // Assuming 'user_id' is the column in "database_user" table representing the user's ID
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $selectedValues[] = $row['db_id']; // Assuming 'db_id' is the column in "database_user" table representing the selected database ID
        }
        $sql = "SELECT * FROM users, db, user_db $where";
    }else{
        $where = '';

        $sql = "SELECT * FROM db";
        $result = mysqli_query($conn, $sql);
        $dbs = [];
        if ($result) {
            while($row = mysqli_fetch_assoc($result)){
                $dbs[$row['id']] = $row['name'];
            }
        }
        $availableValues = $dbs;
        $selectedValues = [];
        $sql = "SELECT * FROM users, db, user_db";
    }
    
    
    $lvls = ['10'=>'Admin','9'=>'Manager','8'=>'Auditor','2'=>'Viewer','1'=>'User',];
    
    
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    }
    // get columns of the result
    $Columns =  array_keys($row);

    ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                  
                
                    <form action="<?php echo $url; ?>users/update" method="post">
                        <?php
                        if(!$id){  $required = 'required'; $requiredbolean = true;}else{ $required = ''; $requiredbolean = false;}
                        if($id){ ?><input type="hidden" name="id" value="<?php echo $id; ?>"><?php }
                        
                        FormsInput('username','Name', 'text', $requiredbolean , 'col-4 ',false,1,4);
                        ?>
                        <div class="col-4">
                            <label class="form-label">
                                Password
                            </label>
                            <input type="password" class="form-control mb-3" name="password" id="password" <?php echo $required; ?> >
                        </div>
                        <?php
                        FormsSelect('userlevel','User Level', $lvls , true, 'col-4');
                    
                        // Step 3: Display multiple select or checkboxes with the available values
                        ?>
                        <div class="col-4">
                            <select class="single-select " role="textbox" multiple name="databases[]">
                            <?php
                            foreach ($availableValues as $id => $name) {
                                $selected = in_array($id, $selectedValues) ? 'selected' : '';
                                ?><option value="<?php echo $id; ?>" <?php echo $selected; ?>> <?php echo $name;?></option>';
                            <?php } ?>
                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary px-5" value=""><?php if($id){ ?>Add<?php }else{ ?>Save<?php } ?></button>
                    </form>
                
                    <?php } ?>
           </div>
            </div>
        </div>
    </div>

