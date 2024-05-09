<?php

function lang($phrase)
{
  static $lang = array(
  // Login Page Phrases
  'ADMIN_LOGIN'   => 'Admin Login',
  'ADMIN_USER'    => 'Username',
  'ADMIN_PASS'    => 'Password',
  'LOG_BTN'       => 'Login',
  // Dashboard Page Phrases
  'HOME'    => 'ADMIN',
  'Categories'      => 'Categories',
  'ITEMS'         => 'Items',
  'MEMBERS'       => 'Members',
  'STATISTICS'    => 'Statistics',
  'LOGS'          => 'Logs',
  'EDIT_PROFILE'  => 'Edit Profile',
  'SETTINGS'      => 'Settings',
  'LOGOUT'        => 'Logout',
  // Members Page Phrases
  'ADD_MEMBER'    => 'Add Member',
  'ID'            => 'ID',
  'USERNAME'      => 'Username',
  'EMAIL'         => 'Email',
  'FULL_NAME'     => 'Full Name',
  'REGISTERED'    => 'Registered Date',
  'CONTROL'       => 'Control',
  'EDIT'          => 'Edit',
  'DELETE'        => 'Delete',
  'ACTIVATE'      => 'Activate',
  'DEACTIVATE'    => 'Deactivate',
  // Add Member Page Phrase 

  );
  

  return $lang[$phrase];


}
