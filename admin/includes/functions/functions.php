<?php

/*
  ** Title Function
  ** Title Function That Echo The Page Title In Case The Page
  ** Has The Variable $pageTitle And Echo Defult Title For Other Pages
  */

function getTitle()
{

  global $pageTitle;  // acsessable from any whare

  if (isset($pageTitle)) {

    echo $pageTitle;
  } else {

    echo 'Default';
  }
}

function addNewUser($data) {
  global $connect; // Assuming $connect is your PDO connection

  // Prepare your SQL statement
  $stmt = $connect->prepare("INSERT INTO users (username, password, email, full_name) VALUES (:username, :password, :email, :full_name)");

  // Bind the parameters
  $stmt->bindParam(':username', $data['username']);
  $stmt->bindParam(':password', $data['password']); // Note: Consider using a more secure hashing algorithm like password_hash()
  $stmt->bindParam(':email', $data['email']);
  $stmt->bindParam(':full_name', $data['full-name']);

  // Execute the statement
  if ($stmt->execute()) {
      return true; // Success
  } else {
      return false; // Failure
  }
}
/*
  ** Home Redirect Function v1.0
  ** This Function Accept Parameters
  ** $SuccessMsg = Echo The  Message
  ** $seconds = Seconds Before Redirecting
  */

function redirectHomeSuccess($successMsg, $url = null, $seconds = 3, $pageName = 'Homepage')
{
  
  if ($url === null) {
    $url = 'index.php';
  }
  echo "$successMsg";

  echo "<div class='alert bg-success '>You Will Be Redirected To $pageName  After $seconds Seconds.</div>";

  header("refresh:$seconds;url=$url");

  exit();
}


/*
  ** Home Redirect Function v2.0
  ** This Function Accept Parameters
  ** $msg = Echo The Message [ Error | Success | Warning ]
  ** $url = The Link You Want To Redirect To
  ** $seconds = Seconds Before Redirecting
  */

function redirectHome($msg, $url = null , $seconds = 3,$pageName = 'Homepage')
{
  // validate the url
  if ($url === null) {
    $url = 'index.php';
  } 
  echo $msg;

  echo "<div class='alert bg-warning '>You Will Be Redirected To $pageName  After $seconds Seconds.</div>";

  header("refresh:$seconds;url=$url");

  exit();
}

/*
  ** Check Items Function v1.0
  ** Function To Check Item In Database [ Function Accept Parameters ]
  ** $select = The Item To Select [ Example: user, item, category ]
  ** $from = The Table To Select From [ Example: users, items, categories ]
  ** $value = The Value Of Select [ Example: Amira, Box, Electronics ]
  ** $connect = PDO Connection
  */

function checkItem($select, $from, $value, $connect) {
  $statement = $connect->prepare("SELECT $select FROM $from WHERE $select = ?");
  $statement->execute(array($value));
  return $statement->rowCount();
}


/*
  ** Count Number Of Items Function v1.0
  ** Function To Count Number Of Items Rows
  ** $item = The Item To Count
  ** $table = The Table To Choose From
  ** $connect = PDO Connection
  */

function countItems($item, $table, $connect)
{
  $stmt2 = $connect->prepare("SELECT COUNT($item) FROM $table");
  $stmt2->execute();
  return $stmt2->fetchColumn();
}


/*
  ** Get Latest Records Function v1.0
  ** Function To Get Latest Items From Database [ Users, Items, Comments ]
  ** $select = Field To Select
  ** $table = The Table To Choose From
  ** $order = The Desc Ordering
  ** $limit = Number Of Records To Get
  ** $connect = PDO Connection
  */

function getLatest($select, $table, $order, $limit = 5, $connect)
{
  $getStmt = $connect->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
  $getStmt->execute();
  $rows = $getStmt->fetchAll();
  return $rows;
}


/*
  ** Get All Records Function v1.0
  ** Function To Get All Records From Any Database Table
  ** $table = The Table To Select From
  ** $connect = PDO Connection
  */

function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField, $ordering = "DESC", $limit = NULL, $connect)
{
  $sql = $where == NULL ? '' : $where;
  $sql .= $and == NULL ? '' : $and;
  $sql = $limit == NULL ? '' : $limit;
  $stmt = $connect->prepare("SELECT $field FROM $table $sql ORDER BY $orderField $ordering $limit");
  $stmt->execute();
  $rows = $stmt->fetchAll();
  return $rows;
}