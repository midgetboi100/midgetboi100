<div class="navbar">
    <div class="leftnav">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="producers.php">Producers</a>
    </div>

    <div class="rightnav">

        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION["ProducerID"])) { ?>
            <a href="producer_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>

        <?php } elseif (isset($_SESSION["CustomerID"])) { ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>

        <?php } else { ?>
            <a href="login.php">Login</a>
        <?php } ?>

    </div>
</div>