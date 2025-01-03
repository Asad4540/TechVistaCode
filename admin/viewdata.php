<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techvistacode"; // Change to your database name

session_start();

// Check if the admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Set timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Define the number of results per page (25 entries per page                                                                )
$results_per_page = 10;

// Check for search query
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

// Calculate the total number of pages
$sql_total = "SELECT COUNT(*) AS total FROM formdata WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR contact LIKE '%$search%' OR message LIKE '%$search%'";
$result_total = $conn->query($sql_total);
$total_rows = $result_total->fetch_assoc()["total"];
$number_of_pages = ceil($total_rows / $results_per_page);

// Determine which page the user is on
$page =
    isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int) $_GET["page"] : 1;
$start_from = ($page - 1) * $results_per_page;

// Fetch data for the current page and search
$sql = "SELECT * FROM formdata 
    WHERE name LIKE '%$search%' OR 
    email LIKE '%$search%' OR
    contact LIKE '%$search%' OR
    message LIKE '%$search%' 
    ORDER BY created_at DESC
    LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechVistaCode</title>
    <link rel="icon" type="image/png" href="./images/tvc-favicon.webp">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 30px;
            color: #0056b3;
            text-align: center;
        }

        table {
            margin-bottom: 20px;
            width: 100%;
        }

        .table th {
            background-color: #0056b3;
            color: #fff;
            text-align: center;
        }

        .pagination {
            justify-content: center;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-container input[type="search"] {
            width: 100%;
            max-width: 400px;
            padding: 5px;
        }

        .search-container button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 6px 12px;
        }

        .search-container button:hover {
            background-color: #003b7d;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-container input[type="search"] {
                /* margin-bottom: 10px; */
                width: 100%;
            }

            .table {
                font-size: 0.9rem;
            }

            .table th,
            .table td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="display:flex;justify-content: flex-end;"><a href='logout.php' class="btn btn-secondary">Logout</a>
        </div><br>
        <center>
            <div style="width:200px"><img src="../images/tvc-logo.webp" style="width:200px" alt=""></div>
        </center>
        <h2>Contact Form Submissions</h2>

        <!-- Search Form -->

        <form method="GET" action="" class="search-container">
            <div style="display:flex;align-items:center;justify-content:center;gap:20px;width:100%">
                <input type="search" name="search" class="form-control" placeholder="Search by name, email, or contact"
                    value="<?php echo htmlspecialchars(
                        $search
                    ); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Table for form submissions -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Message</th>
                        <th>Timestamp</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        // Initialize the serial number based on the current page
                        $serial_no = ($page - 1) * $results_per_page + 1;

                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            // Convert and format created_at to IST and desired format
                            $created_at_ist = date('H:i:s d/m/y', strtotime($row['created_at']));

                            echo "<tr>
                    <td>" .
                                $serial_no .
                                "</td> <!-- Serial number -->
                    <td>" .
                                htmlspecialchars($row["name"]) .
                                "</td>
                    <td>" .
                                htmlspecialchars($row["email"]) .
                                "</td>
                    <td>" .
                                htmlspecialchars($row["contact"]) .
                                "</td>
                    <td>" .
                                htmlspecialchars($row["message"]) .
                                "</td>
                    <td>" .
                                htmlspecialchars($created_at_ist) .
                                "</td>

                  </tr>";

                            // Increment serial number for the next row
                            $serial_no++;
                        }
                    } else {
                        echo "<tr><td colspan='5'>No data found</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php // Display pagination
                for ($i = 1; $i <= $number_of_pages; $i++) {
                    $active = $i == $page ? "active" : "";
                    echo "<li class='page-item $active'><a class='page-link' href='?page=$i&search=" .
                        urlencode($search) .
                        "'>$i</a></li>";
                } ?>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close();
?>