<?php 

include_once('../includes/header.php');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect to index.php if no user found
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('index.php'));
    exit;
}

?>

<section class="text-center py-32">
    <h1 class="font-bold text-3xl md:text-4xl lg:text-5xl pb-7">View Ticket</h1>

</section>



<?php 

include_once('../includes/footer.php');

?>