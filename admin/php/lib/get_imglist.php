<?php
include '../../../php/configs/db.php';

$start = isset($_POST['starting']) ? (int)$_POST['starting'] : 0;
$perpage = 9;
$output = "";
$modals = "";

// Adjust query to include the starting point and per page limit
$query = $con->prepare("SELECT `id`, `product_id`, `image` FROM `multiple_images` ORDER BY `id` DESC LIMIT ?, ?");
$query->bind_param("ii", $start, $perpage);
$query->execute();
$result = $query->get_result();

while ($data = $result->fetch_assoc()) {
    $i_id = $data['id'];
    $product_id = $data['product_id'];
    $image = htmlspecialchars($data['image']);

    // Secure product query
    $productQuery = $con->prepare("SELECT `title` FROM `products` WHERE `id` = ?");
    $productQuery->bind_param("i", $product_id);
    $productQuery->execute();
    $productResult = $productQuery->get_result();
    $product = $productResult->fetch_assoc();
    $productTitle = htmlspecialchars($product['title']);
    
    // Create table row
    $output .= "
    <tr>
        <td onclick=\"window.location.href = '../assets/img/related_images/{$image}'\">
            <img src=\"../assets/img/related_images/{$image}\" class=\"img-thumbnail\" width=\"100\" alt=\"img\">
        </td>
        <td>{$productTitle}</td>
        <td>
            <a href=\"#\" data-toggle=\"modal\" data-target=\"#deleteModel{$i_id}\" class=\"btn btn-danger btn-sm btn-circle\">
                <i class=\"fas fa-trash\"></i>
            </a>
        </td>
    </tr>";

    // Create modal
    $modals .= "
    <div class=\"modal fade\" id=\"deleteModel{$i_id}\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"deleteModelLabel{$i_id}\" aria-hidden=\"true\">
        <div class=\"modal-dialog\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h5 class=\"modal-title\" id=\"deleteModelLabel{$i_id}\">Are you sure you want to delete {$productTitle}?</h5>
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>
                </div>
                <div class=\"modal-footer\">
                    <button type=\"button\" onclick=\"window.location.href = 'products_image_list.php?delete_product=true&pid={$i_id}&img={$image}'\" class=\"btn btn-danger\">Delete Now</button>
                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>
                </div>
            </div>
        </div>
    </div>";
}

echo json_encode(['rows' => $output, 'modals' => $modals]);
?>
