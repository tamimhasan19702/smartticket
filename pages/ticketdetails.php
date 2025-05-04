<?php 

include_once('../includes/header.php');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect to index.php if no user found
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('index.php'));
    exit;
}

// Get the ticket ID from the URL
if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Fetch the ticket details from the tickets table
    $ticketQuery = "SELECT * FROM tickets WHERE id = ?";
    $ticketStmt = $pdo->prepare($ticketQuery);
    $ticketStmt->execute([$ticket_id]);
    $ticket = $ticketStmt->fetch();

    if (!$ticket) {
        $_SESSION['error'] = 'Ticket not found.';
        header('Location: ' . url('index.php'));
        exit;
    }

    // Fetch the replies for the ticket from the ticket_replies table
    $repliesQuery = "SELECT tr.*, u.name as user_name FROM ticket_replies tr 
                     LEFT JOIN users u ON tr.user_id = u.id 
                     WHERE tr.ticket_id = ? ORDER BY tr.created_at DESC";
    $repliesStmt = $pdo->prepare($repliesQuery);
    $repliesStmt->execute([$ticket_id]);
    $replies = $repliesStmt->fetchAll();
} else {
    $_SESSION['error'] = 'Ticket ID not provided.';
    header('Location: ' . url('index.php'));
    exit;
}

?>

<section class="text-gray-400 bg-gray-900 body-font h-screen flex items-center justify-center">
    <div class="container px-5 py-24 mx-auto flex items-center justify-center gap-5 flex-col md:flex-row">

        <!-- Ticket Details Section -->
        <div class="lg:w-1/2 md:w-1/2 text-center mb-8 md:mb-0">
            <h2 class="text-3xl font-medium mb-4">Ticket ID: <?php echo htmlspecialchars($ticket['id']); ?></h2>
            <p class="mb-8"><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['subject']); ?></p>
            <p class="mb-8"><strong>Description:</strong> <?php echo htmlspecialchars($ticket['description']); ?></p>
            <p class="mb-8"><strong>Status:</strong> <?php echo htmlspecialchars($ticket['status']); ?></p>
            <p class="mb-8"><strong>Created At:</strong> <?php echo htmlspecialchars($ticket['created_at']); ?></p>
        </div>

        <!-- Ticket Replies Section -->
        <div class="lg:w-1/2 md:w-1/2">
            <h3 class="text-2xl font-medium text-white title-font mb-4">Ticket Replies</h3>

            <?php if (count($replies) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($replies as $reply): ?>
                <div class="bg-gray-800 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span
                            class="font-semibold text-indigo-400"><?php echo htmlspecialchars($reply['user_name']); ?></span>
                        <span
                            class="text-gray-500 text-sm ml-2"><?php echo htmlspecialchars($reply['created_at']); ?></span>
                    </div>
                    <p class="text-gray-400"><?php echo nl2br(htmlspecialchars($reply['reply_text'])); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500">No replies yet.</p>
            <?php endif; ?>

            <!-- Add New Reply Form (if you want users to reply) -->
            <form action="submit_reply.php" method="POST" class="mt-6">
                <textarea name="reply_text" class="w-full p-4 bg-gray-800 text-gray-300 border rounded-lg"
                    placeholder="Add a reply..." rows="4" required></textarea>
                <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['id']); ?>">
                <button type="submit" class="mt-4 px-6 py-2 bg-indigo-500 text-white rounded-lg">Submit Reply</button>
            </form>
        </div>

    </div>
</section>

<?php 

include_once('../includes/footer.php');

?>