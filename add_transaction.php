<?php session_start(); if(!isset($_SESSION['logged']) || !$_SESSION['logged']){ header("Location: ./index.php"); }else{ /* -- SQL Config -- */ $servername = "localhost"; $username = "root"; $password = "root123"; $dbname = "cs634dmadmin"; $USER_DB = $_SESSION['User_DB']; $conn = new mysqli($servername, $username, $password, $USER_DB); if ($conn->connect_error) { echo "<hr/><br/>500 - Internal Server Error. <br/> Cannot connect to MySQL. Please do proper setup of XAAMP again.<br/><hr/>"; die("Connection failed: " . $conn->connect_error); }else{ if($conn->query("INSERT INTO Transactions VALUES()") && $result = $conn->query("SELECT LAST_INSERT_ID() AS last_val")){ echo "Success: in creating a Transaction request. (updating Transactions)<br/>"; $items = ""; $Tid = $result->fetch_array()['last_val']; for($i=0;$i<(count($_POST['cart'])-1);$i++){ $items = $items."(".$Tid.",'".$_POST['cart'][$i]."') ,"; }$items = $items."(".$Tid.",'".$_POST['cart'][$i]."')"; if($conn->query("INSERT INTO TransactionDetails VALUES ".$items)){ echo "Success: in creating a Adding items with the Transaction request. (updating TransactionDetails)<br/>"; echo "Redirecting to shopping page in 5 seconds."; header("refresh: 5;url=./shopping.php"); }else{ echo "Error: in creating a Adding items with the Transaction request. (updating TransactionDetails)<br/>"; echo "Redirecting to shopping page in 5 seconds."; header("refresh: 5;url=./shopping.php"); } }else{ echo "Error: in creating a Transaction request. (updating Transactions)<br/>"; echo "Redirecting to shopping page in 5 seconds."; header("refresh: 5;url=./shopping.php"); } } }?>