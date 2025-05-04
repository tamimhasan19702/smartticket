<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= url('public/img/favicon.ico') ?>" type="image/x-icon">

    <!-- TailwindCSS -->
    <link rel="stylesheet" href="<?= url('public/css/output.css') ?>">
    <link rel="stylesheet" href="<?= url('public/css/styles.css') ?>">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav
        class="flex flex-col sm:flex-row flex-wrap md:items-center px-4 md:px-5 lg:px-16 py-2 justify-between border-b border-gray-600 fixed top-0 z-20 w-full bg-slate-900">
        <div class="flex items-center justify-between sm:justify-around gap-x-2 text-white">
            <!-- Home Link -->
            <a href="<?= url('index.php') ?>" class="py-1 flex items-center text-2xl">
                Support Ticket System
            </a>

            <!-- Hamburger -->
            <button class="sm:hidden cursor-pointer flex flex-col justify-center gap-y-1" id="hamburger"
                aria-label="menu">
                <div class="w-6 h-1 rounded-lg bg-white bar duration-300"></div>
                <div class="w-4 h-1 rounded-lg bg-white bar duration-300"></div>
                <div class="w-6 h-1 rounded-lg bg-white bar duration-300"></div>
            </button>
        </div>

        <ul class="hidden sm:flex text-gray-400 justify-end items-center gap-x-7 gap-y-5 tracking-wide mt-5 sm:mt-0"
            id="links">
            <?php if (is_logged_in()): ?>
            <?php if (is_admin()): ?>
            <!-- Admin Menu -->
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('pages/adminDashboard.php') ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?> hover:text-gray-300">
                    Admin Dashboard
                </a>
            </li>
            <?php else: ?>
            <!-- Logged-in User Menu -->
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('pages/submitticket.php') ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'submitticket.php' ? 'active' : '' ?> hover:text-gray-300">
                    Submit Ticket
                </a>
            </li>
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('pages/viewTickets.php') ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'viewTickets.php' ? 'active' : '' ?> hover:text-gray-300">
                    View Tickets
                </a>
            </li>
            <?php endif; ?>

            <!-- Common Logout Link -->
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('pages/logout.php') ?>" class="hover:text-gray-300">Logout</a>
            </li>

            <?php else: ?>
            <!-- Guest Menu -->
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('pages/signup.php') ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'signup.php' ? 'active' : '' ?> hover:text-gray-300">
                    Sign Up
                </a>
            </li>
            <li class="table-caption mb-5 sm:mb-0">
                <a href="<?= url('index.php') ?>"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : '' ?> hover:text-gray-300">
                    Sign In
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </nav>

    <main class="h-screen md:h-100vh">