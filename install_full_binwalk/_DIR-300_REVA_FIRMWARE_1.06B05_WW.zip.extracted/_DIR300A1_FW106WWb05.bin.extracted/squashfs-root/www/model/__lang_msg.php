<?
require("/www/model/__g_var.php");
/* If the multiple languages are built-in, the langcode is configured by user. */
/* 1. set to default langcode */
$__LOCALE_LANGUAGE = fread("/etc/config/langcode");
/* 2. if no default value, use user configured value. */
if ($__LOCALE_LANGUAGE=="") { $__LOCALE_LANGUAGE = query("/sys/langcode"); }
/* 3. if no user configured, the alternated (external) lang pack is installed.
 *    check the lang pack setting. */
if ($__LOCALE_LANGUAGE=="")
{
	/* get the lang pack info. */
	$CHARSET = fread("/www/locale/alt/charset");
	$LANGCODE = fread("/www/locale/alt/langcode");
	if ($CHARSET == "") { $__LOCALE_LANGUAGE = "en"; }
	else { $__LOCALE_LANGUAGE = "alt"; }
}
else
{
	$LANGCODE = $__LOCALE_LANGUAGE;
}

/* the langcode is decided, load the language */
$LOCALE_PATH="/www/locale/".$__LOCALE_LANGUAGE;
$CHARSET=fread($LOCALE_PATH."/charset");
if ($CHARSET=="") {$CHARSET="utf-8";}
if ($MY_MSG_FILE!="")
{
	if ($__LOCALE_LANGUAGE!="en")
	{
		require("/www/locale/en/msg_comm.php");
		require("/www/locale/en/msg_menu.php");
		require("/www/locale/en/".$MY_MSG_FILE);
	}
	require($LOCALE_PATH."/msg_comm.php");
	require($LOCALE_PATH."/msg_menu.php");
	require($LOCALE_PATH."/".$MY_MSG_FILE);
}
?>
