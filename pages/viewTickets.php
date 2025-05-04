<?php 
include_once('../includes/header.php');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('index.php'));
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = 'User not found.';
    header('Location: ' . url('index.php'));
    exit;
}

// Destructure user info
$username = $user['name'];
$email = $user['email'];
$role = $user['role'];

// Fetch user's tickets
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>

<section class="text-gray-400 bg-gray-900 body-font h-screen flex items-center justify-start">
    <div class="container px-5 mx-auto flex flex-wrap">

        <!-- Heading Section -->
        <div class="flex flex-col text-center w-full mb-20">
            <h2 class="text-xs text-indigo-400 tracking-widest font-medium title-font mb-1">Your Tickets</h2>
            <h1 class="sm:text-3xl text-2xl font-medium title-font text-white">All Your Submitted Tickets</h1>
        </div>

        <!-- Tickets Section -->
        <div class="flex flex-wrap -m-4 w-full gap-5 mt-5">

            <?php if ($tickets): ?>
            <?php foreach ($tickets as $ticket): ?>
            <div class="p-4 md:w-1/3 my-5">
                <div class="flex rounded-lg h-full bg-gray-800 bg-opacity-60 p-8 flex-col">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                            </svg>
                        </div>
                        <h2 class="text-white text-lg title-font font-medium">Ticket ID: <?php echo $ticket['id']; ?>
                        </h2>
                    </div>
                    <div class="flex-grow">
                        <p class="leading-relaxed text-base"><strong>Subject:</strong>
                            <?php echo htmlspecialchars($ticket['subject']); ?></p>
                        <p class="leading-relaxed text-base"><strong>Description:</strong>
                            <?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
                        <p class="leading-relaxed text-base"><strong>Status:</strong>
                            <?php echo htmlspecialchars($ticket['status']); ?></p>
                        <p class="leading-relaxed text-base"><strong>Created At:</strong>
                            <?php echo htmlspecialchars($ticket['created_at']); ?></p>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="p-4 w-full">
                <div class="text-center text-gray-500">No tickets found.</div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>



<?php include_once('../includes/footer.php'); ?>