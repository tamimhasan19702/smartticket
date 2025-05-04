<?php 
require_once __DIR__ . '/config/db.php'; 
require_once __DIR__ . '/config/config.php'; 
require_once __DIR__ . '/includes/functions.php'; 
include_once('./includes/header.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    // Validate email format
    if (!validate_email($email)) {
        $_SESSION['error'] = 'Invalid email format.';
    } else {
        // Fetch user from DB
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && verify_password($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            // Check user role and redirect
            if ($user['role'] === 'admin') {
                // Redirect to admin dashboard
                header('Location: ' . url('pages/adminDashboard.php'));
                exit;
            } else {
                // Redirect to view tickets page for regular users
                header('Location: ' . url('pages/viewTickets.php'));
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password.';
        }
    }
}
?>

<section class="text-gray-400 bg-gray-900 body-font h-screen md:h-100vh flex items-center justify-center">
    <div class="container px-5 py-24 mx-auto flex items-center justify-center gap-5 flex-col md:flex-row">

        <!-- Left Side Text -->
        <div class="lg:w-1/2 md:w-1/2 text-center mb-8 md:mb-0">
            <h2 class="text-3xl font-medium mb-4">Need Help? Create a Support Ticket</h2>
            <p class="mb-8">If you encounter any issues, feel free to reach out to our support team. We're here to
                assist you!</p>
        </div>

        <!-- Right Side Sign In Form -->
        <div
            class="lg:w-1/2 md:w-1/2 bg-gray-800 bg-opacity-50 rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
            <h2 class="text-white text-lg font-medium title-font mb-5">Sign In</h2>

            <!-- Display Error Message -->
            <?php if (!empty($_SESSION['error'])): ?>
            <div class="text-red-400 mb-4"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Email -->
                <div class="relative mb-4">
                    <label for="email" class="leading-7 text-sm text-gray-400">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <!-- Password -->
                <div class="relative mb-4">
                    <label for="password" class="leading-7 text-sm text-gray-400">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Sign
                    In</button>
            </form>

            <!-- Sign Up Link -->
            <p class="text-xs mt-3">Don't have an account?
                <a href="<?php echo url('pages/signup.php') ?>" class="text-indigo-500 hover:text-indigo-600">Sign
                    Up</a>
            </p>
        </div>
    </div>
</section>

<?php 
include_once('../includes/footer.php');
?>