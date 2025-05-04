<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    $role = sanitize($_POST['role']);

    if (!validate_email($email)) {
        $_SESSION['error'] = 'Invalid email address.';
        header('Location: ' . url('pages/signup.php'));
        exit; // Ensure no further code runs after this
    }

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['error'] = 'Email is already taken.';
        header('Location: ' . url('pages/signup.php'));
        exit;
    }

    $hashed_password = hash_password($password);

    $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$name, $email, $hashed_password, $role]);
        $_SESSION['success'] = 'Account created successfully! Please log in.';

        // Redirect based on the user's role
        if ($role == 'admin') {
            header('Location: ' . url('pages/adminDashboard.php'));
        } else {
            header('Location: ' . url('pages/viewTickets.php'));
        }
        exit; // Ensure no further code runs after the redirection
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error creating account: ' . $e->getMessage();
        header('Location: ' . url('pages/signup.php'));
        exit;
    }
}

include_once('../includes/header.php');
?>

<section class="text-gray-400 bg-gray-900 body-font h-screen flex items-center justify-center">
    <div class="container px-5 py-24 mx-auto flex items-center justify-center gap-5 flex-col md:flex-row">

        <!-- Left: Info Text -->
        <div class="lg:w-1/2 md:w-1/2 text-center mb-8 md:mb-0">
            <h2 class="text-3xl font-medium mb-4">Need Help? Create a Support Ticket</h2>
            <p class="mb-8">If you encounter any issues, feel free to reach out to our support team.</p>
        </div>

        <!-- Right: Sign Up Form -->
        <div
            class="lg:w-1/2 md:w-1/2 bg-gray-800 bg-opacity-50 rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
            <h2 class="text-white text-lg font-medium title-font mb-5">Sign Up</h2>

            <!-- Success or Error Message -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-600 text-white p-3 mb-4 rounded"><?php echo $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
            <?php elseif (isset($_SESSION['error'])): ?>
            <div class="bg-red-600 text-white p-3 mb-4 rounded"><?php echo $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="<?php echo url('pages/signup.php'); ?>">
                <!-- Full Name -->
                <div class="relative mb-4">
                    <label for="name" class="leading-7 text-sm text-gray-400">Full Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

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

                <!-- Role -->
                <div class="relative mb-4">
                    <label for="role" class="leading-7 text-sm text-gray-400">Role</label>
                    <select id="role" name="role" required
                        class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-3 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <option style="color: black" value="user">User</option>
                        <option style="color: black" value="admin">Admin</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button
                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                    Sign Up
                </button>
            </form>

            <!-- Sign In Link -->
            <p class="text-xs mt-3">Already have an account? <a href="<?php echo url('index.php') ?>"
                    class="text-indigo-500 hover:text-indigo-600">Sign In</a></p>
        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>