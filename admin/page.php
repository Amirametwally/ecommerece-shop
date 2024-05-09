<?php
// Initialize $action with the value from $_GET['action'] if it exists, otherwise set it to an empty string.
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Use a switch statement for better performance.
switch ($action) {
    case 'manage':
        echo 'Welcome You are in Manage Page ';
        echo '<a href="?action=add">Add New Category +</a>';
        break;
    case 'add':
        echo 'Welcome You are in Add Page';
        break;
    case 'edit':
        echo 'Welcome You are in Edit Page';
        break;
    case 'delete':
        echo 'Welcome You are in Delete Page';
        break;
    default:
        echo '<p>Error<br>No Page founded with this name</p>';
}
