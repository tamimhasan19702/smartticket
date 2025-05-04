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

// Handling ticket submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_subject = sanitize($_POST['subject']);
    $ticket_description = sanitize($_POST['description']);

    // Validate the form data
    if (empty($ticket_subject) || empty($ticket_description)) {
        $_SESSION['error'] = 'Subject and description are required.';
        header('Location: ' . url('pages/submitticket.php'));
        exit;
    }

    // Insert ticket into the database
    $query = "INSERT INTO tickets (user_id, subject, description, status) 
          VALUES (?, ?, ?, 'open')";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$user_id, $ticket_subject, $ticket_description]);
        $_SESSION['success'] = 'Ticket submitted successfully!';
        header('Location: ' . url('pages/viewTickets.php')); // Redirect to view tickets page
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error submitting ticket: ' . $e->getMessage();
        header('Location: ' . url('pages/submitticket.php'));
        exit;
    }
}
?>

<section class="text-gray-400 bg-gray-900 body-font h-screen flex items-center justify-center">
    <div class="container px-5 py-24 mx-auto flex items-center justify-center gap-5 flex-col md:flex-row">
        <!-- Left: Info Text -->
        <div class="lg:w-1/2 md:w-1/2 text-center mb-8 md:mb-0">
            <h2 class="text-3xl font-medium mb-4">Welcome, <?php echo htmlspecialchars($username); ?>
                (<?php echo htmlspecialchars($role); ?>)</h2>
            <p class="mb-8">Your email: <?php echo htmlspecialchars($email); ?></p>
        </div>

        <!-- Right: Ticket Submission Form -->
        <div
            class="lg:w-1/2 md:w-1/2 bg-gray-800 bg-opacity-50 rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
            <h2 class="text-white text-lg font-medium title-font mb-5">Submit a Ticket</h2>

            <!-- Success or Error Message -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-600 text-white p-3 mb-4 rounded"><?php echo $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
            <?php elseif (isset($_SESSION['error'])): ?>
            <div class="bg-red-600 text-white p-3 mb-4 rounded"><?php echo $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Ticket Form -->
            <form method="POST" action="<?php echo url('pages/submitticket.php'); ?>">
                <!-- Ticket Subject -->
                <div class="relative mb-4">
                    <label for="subject" class="leading-7 text-sm text-gray-400">Subject</label>
                    <input type="text" id="subject" name="subject" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <!-- Ticket Description -->
                <div class="relative mb-4">
                    <label for="description" class="leading-7 text-sm text-gray-400">Description</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></textarea>
                </div>

                <!-- Submit Button -->
                <button
                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                    Submit Ticket
                </button>
            </form>
        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>