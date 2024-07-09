<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examinees</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>List of Examinees</h2>
        <?php
            // Display error message
            $error = isset($_GET['error']) ? $_GET['error'] : '';
            if (!empty($error)) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>$error
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
            }

            // Display success message
            $success = isset($_GET['success']) ? $_GET['success'] : '';
            if (!empty($success)) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>$success
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
            }
        ?>
        <a class="btn btn-primary" href="/jazzphp/Admin/create.php" role="button">Create New Examinee</a>
        <a class="btn btn-secondary" href="index.html" style="position: absolute; top: 20px; right: 20px;" role="button">Back to Dashboard</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "idgenerate";

                // Create connection
                $connection = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                // read all row from database table
                $sql = "SELECT * FROM examinees";
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                // read data of each row
                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[id]</td>
                        <td>$row[name]</td>
                        <td>$row[email]</td>
                        <td>$row[phone]</td>
                        <td>$row[address]</td>
                        <td>$row[created_at]</td>
                        <td>
                            <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal$row[id]'>Edit</button>
                            <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#examineeModal$row[id]'>View</button>
                            <a class='btn btn-danger btn-sm' href='/jazzphp/Admin/delete.php?id=$row[id]' onclick='return confirmDelete()'>Delete</a>
                        </td>
                    </tr>
                    ";

                    // Modal for each examinee
                    echo "
                    <div class='modal fade' id='examineeModal$row[id]' tabindex='-1' aria-labelledby='examineeModalLabel$row[id]' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='examineeModalLabel$row[id]'>Examinee Details</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <p><strong>Name:</strong> $row[name]</p>
                                    <p><strong>Email:</strong> $row[email]</p>
                                    <p><strong>Phone:</strong> $row[phone]</p>
                                    <p><strong>Address:</strong> $row[address]</p>
                                    <p><strong>Created At:</strong> $row[created_at]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";

                    // Modal for edit
                    echo "
                    <div class='modal fade' id='editModal$row[id]' tabindex='-1' aria-labelledby='editModalLabel$row[id]' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel$row[id]'>Edit Examinee</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <form id='editForm$row[id]' action='/jazzphp/Admin/update.php' method='post' onsubmit='return validateForm$row[id]()'>
                                    <input type='hidden' name='id' value='$row[id]'>
                                    <div class='mb-3'>
                                        <label for='newId' class='form-label'>New ID</label>
                                        <input type='text' class='form-control' id='newId$row[id]' name='newId' value='$row[id]'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='name' class='form-label'>Name</label>
                                        <input type='text' class='form-control' id='name$row[id]' name='name' value='$row[name]'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='email' class='form-label'>Email</label>
                                        <input type='email' class='form-control' id='email$row[id]' name='email' value='$row[email]'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='phone' class='form-label'>Phone</label>
                                        <input type='text' class='form-control' id='phone$row[id]' name='phone' value='$row[phone]'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='address' class='form-label'>Address</label>
                                        <textarea class='form-control' id='address$row[id]' name='address'>$row[address]</textarea>
                                    </div>
                                    <button type='submit' class='btn btn-primary'>Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <script>
        <?php
            // form validation for each row
            while($row = $result->fetch_assoc()) {
                echo "
                function validateForm$row[id]() {
                    var newId = document.getElementById('newId$row[id]').value;
                    var name = document.getElementById('name$row[id]').value;
                    var email = document.getElementById('email$row[id]').value;
                    var phone = document.getElementById('phone$row[id]').value;
                    var address = document.getElementById('address$row[id]').value;

                    if (newId.trim() == '' || name.trim() == '' || email.trim() == '' || phone.trim() == '' || address.trim() == '') {
                        var alertBox = document.createElement('div');
                        alertBox.classList.add('alert', 'alert-danger', 'alert-dismissible', 'fade', 'show');
                        alertBox.setAttribute('role', 'alert');
                        alertBox.innerHTML = 'All fields are required<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>';
                        document.body.appendChild(alertBox);
                        return false; // Prevent form submission
                    }
                    // If all fields are filled, return true to submit the form
                    return true;
                }
                ";
            }
        ?>
    </script>
    
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }
    </script>

</body>
</html>
