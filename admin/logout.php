<?
include('lib/includes.php');
session_destroy();
session_unset();
@session_start();
setMessage("<div class='success'>Logout efetuado com sucesso.</div>");
header("Location: index.php");
?>