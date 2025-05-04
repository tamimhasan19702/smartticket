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

<section class="w-full bg-gray-900 text-gray-400 py-20 h-screen ">
    <div class=" mx-10 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">



        <!-- Tickets Section -->
        <div class="w-full md:w-[70%] bg-gray-800 bg-opacity-50 p-6 sm:p-8 rounded-lg overflow-x-auto">
            <h2 class="text-white text-lg sm:text-xl font-medium mb-5">🎫 Your Submitted Tickets</h2>

            <table class="table-auto w-full text-left text-sm sm:text-base text-gray-400">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Subject</th>
                        <th class="px-3 py-2">Description</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tickets): ?>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                        <td class="px-3 py-2"><?php echo $ticket['id']; ?></td>
                        <td class="px-3 py-2"><?php echo htmlspecialchars($ticket['subject']); ?></td>
                        <td class="px-3 py-2"><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></td>
                        <td class="px-3 py-2 capitalize"><?php echo htmlspecialchars($ticket['status']); ?></td>
                        <td class="px-3 py-2"><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center px-3 py-4 text-gray-500">No tickets found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>


<?php include_once('../includes/footer.php'); ?>