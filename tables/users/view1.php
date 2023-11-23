<?php
// Query to fetch users with the count of databases they have access to and the database names
$query = "SELECT users.id, users.username, users.userlevel, COUNT(user_db.db_id) AS db_count, GROUP_CONCAT(db.name) AS db_names
          FROM users
          LEFT JOIN user_db ON users.id = user_db.user_id
          LEFT JOIN db ON user_db.db_id = db.id
          GROUP BY users.id, users.username, users.userlevel";
$result = mysqli_query($conn, $query);

// Check if query execution was successful
if ($result) {
    // Fetch all rows as an associative array
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Query execution failed
    echo "Error: " . mysqli_error($conn);
}
?>
<h1>Users Table</h1>
<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Userlevel</th>
                        <th>Database Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users) && !empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php if($userlevel == 10){ echo '<a href="'. $url.'users/edit/'.$user['id'] .'">';}  echo $user['username']; if($userlevel == 10){ echo '</a>';} ?></td>
                                <td><?php echo $user['userlevel']; ?></td>
                                <td>(<?php echo $user['db_count']; ?>) <?php echo $user['db_names']; ?></td>
                            </tr>
                 
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Userlevel</th>
                        <th>Database Access</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
