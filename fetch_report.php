<?php 
require_once(dirname(__FILE__) . '/config.php'); 
if (!isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type'])) {
    echo json_encode([]);
    exit;
}

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $result = mysqli_query($db, $query);
    if ($result) {
        echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>

