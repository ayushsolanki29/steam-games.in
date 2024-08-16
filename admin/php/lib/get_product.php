<?php
include '../../../php/configs/db.php';

$start = isset($_POST['starting']) ? (int)$_POST['starting'] : 0;
$perpage = 10;
$output = "";
$modals = "";

$query = "SELECT `id`, `title`, `price`, `discount`, `description`, `thumbnail`, `category_id`, `keywords`, `twofactor`, `date`, `views` FROM `products` ORDER BY `id` DESC LIMIT $start, $perpage";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        $p_id = $data['id'];
        $p_title = $data['title'];
        $cat_id = $data['category_id'];

        // Generate table row
        $output .= '
        <tr>
            <td>' . htmlspecialchars($data['title']) . '</td>
            <td title="' . htmlspecialchars($data['description']) . '">Mouse Hover here</td>
            <td>' . htmlspecialchars($data['price'] * (1 - $data['discount'] / 100)) . ' ₹</td>';

        // Fetching the category names
        $fetch_cat = mysqli_query($con, "SELECT `category_name` FROM `category` WHERE `id` IN ($cat_id)");
        $categories = [];
        while ($cat_fetch = mysqli_fetch_assoc($fetch_cat)) {
            $categories[] = htmlspecialchars($cat_fetch['category_name']);
        }
        $output .= '<td>' . implode(' | ', $categories) . '</td>';

        $output .= '
            <td title="' . htmlspecialchars($data['keywords']) . '">Mouse Hover here</td>
            <td>' . ($data['twofactor'] ? "Yes" : "No") . '</td>';

        // Counting multiple images for the product
        $query_images = "SELECT COUNT(*) AS totalmultiple_images FROM multiple_images WHERE product_id = '$p_id'";
        $result_images = mysqli_query($con, $query_images);
        $row_images = mysqli_fetch_assoc($result_images);
        $output .= '<td>' . htmlspecialchars($row_images['totalmultiple_images']) . '</td>';

        $output .= '
            <td>' . htmlspecialchars($data['date']) . '</td>
            <td>' . htmlspecialchars($data['views']) . '</td>
            <td>
                <a href="#" data-toggle="modal" data-target="#deleteModel' . htmlspecialchars($data['id']) . '" class="btn btn-danger btn-sm btn-circle">
                    <i class="fas fa-trash"></i>
                </a> </td>
              <td>   <a href="products_edit.php?p=' . htmlspecialchars($data['id']) . '" class="btn btn-primary btn-sm btn-circle">
                    <i class="fas fa-pen"></i>
                </a>
            </td>
        </tr>';

        // Generating modal
        $modals .= '
        <div class="modal fade" id="deleteModel' . htmlspecialchars($data['id']) . '" tabindex="-1" role="dialog" aria-labelledby="deleteModelLabel' . htmlspecialchars($data['id']) . '" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModelLabel' . htmlspecialchars($data['id']) . '">Are you sure you want to delete ' . htmlspecialchars($data['title']) . '?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="window.location.href = \'products_list.php?delete_product=true&pid=' . htmlspecialchars($data['id']) . '&img=' . htmlspecialchars($data['thumbnail']) . '\'" class="btn btn-danger">Delete Now</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>';
    }
}

// Output JSON response
echo json_encode(['rows' => $output, 'modals' => $modals]);
?>
