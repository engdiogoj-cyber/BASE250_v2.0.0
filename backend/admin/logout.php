<?php
/**
 * BASE250 - Logout
 * 
 * Realiza logout e redireciona para login
 */

require_once __DIR__ . '/../includes/auth.php';

logout();

header('Location: login.php');
exit;
