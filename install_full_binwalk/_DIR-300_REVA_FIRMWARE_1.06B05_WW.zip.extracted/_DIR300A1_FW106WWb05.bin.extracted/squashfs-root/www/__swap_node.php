<?
$src_path=$SRC.$NODE_NAME;
$dst_path=$DST.$NODE_NAME;
$src_value=query($src_path);
$dst_value=query($dst_path);
set($src_path, $dst_value);
set($dst_path, $src_value);


echo "<!-- -------------------------------\n";
echo "src_path=".$src_path."\n";
echo "dst_path=".$dst_path."\n";
echo "src_value=".$src_value."\n";
echo "dst_value=".$dst_value."\n";
echo "after set:\n";
echo "src=".query($src_path)."\n";
echo "dst=".query($dst_path)."\n";
echo "-->";
?>
