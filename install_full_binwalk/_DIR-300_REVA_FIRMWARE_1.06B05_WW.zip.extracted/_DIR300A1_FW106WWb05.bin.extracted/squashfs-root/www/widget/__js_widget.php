<?
$salt = query("/runtime/widget/salt");
echo "<script>";
echo "var salt = \"".$salt."\";";
echo "</script>";
?>
