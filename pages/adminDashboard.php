<?php 

include_once('../includes/header.php');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect to index.php if no user found
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('index.php'));
    exit;
}

// Get user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    $username = $user['name'];
    $role = $user['role'];
    $email = $user['email'];
} else {
    $_SESSION['error'] = 'User not found.';
    header('Location: ' . url('index.php'));
    exit;
}

// Fetch all tickets from the database
$ticketQuery = "SELECT * FROM tickets ORDER BY created_at DESC";
$ticketStmt = $pdo->prepare($ticketQuery);
$ticketStmt->execute();
$tickets = $ticketStmt->fetchAll();

?>

<section class="text-gray-400 bg-gray-900 body-font h-screen flex items-center justify-center">
    <div class="container px-5 py-24 mx-auto flex items-center justify-center gap-5 flex-col md:flex-row">

        <!-- Left: Info Text -->
        <div class="lg:w-1/2 md:w-1/2 text-center mb-8 md:mb-0">
            <h2 class="text-3xl font-medium mb-4">Welcome, <?php echo htmlspecialchars($username); ?>
                (<?php echo htmlspecialchars($role); ?>)</h2>
            <p class="mb-8">Your email: <?php echo htmlspecialchars($email); ?></p>
        </div>

        <!-- Right: Admin Dashboard with Ticket List -->
        <div class="lg:w-1/2 md:w-1/2">
            <section class="text-gray-400 bg-gray-900 body-font overflow-hidden">
                <div class="container px-5 py-24 mx-auto">
                    <div class="-my-8 divide-y-2 divide-gray-800">

                        <?php foreach ($tickets as $ticket): ?>
                        <div class="py-8 flex border-t-2 border-gray-800 flex-wrap md:flex-nowrap">
                            <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                                <span class="font-semibold title-font text-white">Ticket ID:
                                    <?php echo htmlspecialchars($ticket['id']); ?></span>
                                <span
                                    class="mt-1 text-gray-500 text-sm"><?php echo htmlspecialchars($ticket['created_at']); ?></span>
                            </div>
                            <div class="md:flex-grow">
                                <h2 class="text-2xl font-medium text-white title-font mb-2">
                                    <?php echo htmlspecialchars($ticket['subject']); ?></h2>
                                <p class="leading-relaxed"><?php echo htmlspecialchars($ticket['description']); ?></p>
                                <a class="text-indigo-400 inline-flex items-center mt-4"
                                    href="ticketdetails.php?id=<?php echo $ticket['id']; ?>">View Details
                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </section>
        </div>

    </div>
</section>

<?php 

include_once('../includes/footer.php');

?>