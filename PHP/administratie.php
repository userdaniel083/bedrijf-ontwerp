<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: inlog.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <link rel="stylesheet" href="../css/administratie.css">
    <meta charset="UTF-8">
    <meta name="viewport"
  content="width=device-width, initial-scale=1.0">
    <title>Workspace kantoor</title>
</head>

<body>
   
</body>

</html>