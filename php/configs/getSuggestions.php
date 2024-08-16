<?php
include 'db.php';

if (isset($_POST['searchIn'])) {
    $searchIn = '%' . mysqli_real_escape_string($con, $_POST['searchIn']) . '%';

    // SQL query with placeholders
    $sql = "SELECT DISTINCT p.title, p.thumbnail, p.id, p.keywords
            FROM products p
            LEFT JOIN category c ON p.category_id = c.id
            WHERE p.title LIKE ?
            OR c.category_name LIKE ?
            OR p.keywords LIKE ?
            OR p.description LIKE ?
            LIMIT 10
            ";

    // Prepare the statement
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssss", $searchIn, $searchIn, $searchIn,$searchIn);

        // Execute the query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Process and display results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Output each suggestion as a clickable item
                echo "<div class='suggestion' onclick='selectSuggestion(\"" . htmlspecialchars($row["title"]) . "\")'>
                        <a href='game_profile.php?p=" . htmlspecialchars($row["id"]) . "'>
                            <img class='searchIMG' src='./assets/img/products/" . htmlspecialchars($row["thumbnail"]) . "' alt='" . htmlspecialchars($row["title"]) . "'>
                            " . htmlspecialchars($row["title"]) . "
                        </a>
                    </div>";
            }
        } else {
            // No results found
            echo "<div class='suggestion'>
                    <a href='store.php'>No suggestions found.</a>
                  </div>
                  <div class='suggestion'>
                    <a href='contact.php'>Want to request \"" .str_replace("%","",htmlspecialchars($searchIn))  . "\"?</a>
                  </div>";
        }

        // Close statement
        $stmt->close();
    } else {
        // Error preparing statement
        echo "Error in preparing statement: " . $con->error;
    }
}


$con->close();
