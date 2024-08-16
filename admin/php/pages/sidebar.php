<style>
    .logo{
        max-width: 100px;
    }
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <img src="img/logo-admin.png" class="logo" alt="">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        content
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fab fa-product-hunt"></i>
            <span>Products </span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage</h6>
                <a class="collapse-item" href="products_list.php">All Products </a>
                <a class="collapse-item" href="products_add.php">Add New</a>
                <a class="collapse-item" href="products_images.php">Upload Images</a>
                <a class="collapse-item" href="products_image_list.php">Manage Images</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-layer-group"></i>
            <span>category</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage</h6>
                <a class="collapse-item" href="category_list.php">List category</a>
                <a class="collapse-item" href="category_add.php">Add New</a>

            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Discount
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-tags"></i>
            <span>Promo codes</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header"></h6> -->
                <a class="collapse-item" href="coupon_codes_add.php">Genrate New</a>

                <!-- <div class="collapse-divider"></div> -->
                <!-- <h6 class="collapse-header">Other Pages:</h6> -->
                <a class="collapse-item" href="coupon_codes_list.php">Manage Codes</a>

            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="banner.php">
            <i class="fas fa-tv"></i>
            <span>Banners</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <div class="sidebar-heading">
        Sells
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsOrder" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-cart-plus"></i>
            <span>Orders</span>
        </a>

        <div id="collapsOrder" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="orders.php">All Orders</a>
                <a class="collapse-item" href="pending_deliveries.php">Pending Deliveries</a>
                <a class="collapse-item" href="delivered.php">Delivered</a>
            </div>
        </div>
    </li>


    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="users_list.php">
            <i class="fas fa-users"></i> 
            <span>users</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="game_reviews.php">
        <i class="fas fa-gamepad"></i>
            <span>Game Reviews</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="contact.php">
            <i class="fas fa-headset"></i>
            <span>contact</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="contact.php">
        <i class="fas fa-bell fa-fw"></i>
            <span>Notifications</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="chat.php">
            <i class="fas fa-comments"></i>
            <span>users chats</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="settings.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span></a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>