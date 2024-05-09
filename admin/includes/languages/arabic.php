<?php

function lang($phrase)
{

  static $lang = array(
  // Login Page Phrases
  'ADMIN_LOGIN'   => 'تسجيل الدخول للوحه التحكم',
  'ADMIN_USER'    => 'اسم المستخدم',
  'ADMIN_PASS'    => 'كلمه المرور',
  'LOG_BTN'       => 'تسجيل الدخول',
  // Dashboard Phrases
  'HOME_ADMIN'    => 'لوحه التحكم',
  'SECTIONS'      => 'الاقسام',
  'ITEMS'         => 'المنتجات',
  'MEMBERS'       => 'الاعضاء',
  'STATISTICS'    => 'الاحصائيات',
  'LOGS'          => 'السجلات',
  'EDIT_PROFILE'  => 'تعديل البروفايل',
  'SETTINGS'      => 'الاعدادات',
  'LOGOUT'        => 'تسجيل الخروج',
  );

  return $lang[$phrase];
}
