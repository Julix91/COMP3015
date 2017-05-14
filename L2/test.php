<?php
if (isset($variable)) {
	echo "step 1 - it is set<br>";
}else{
	echo "step 1 - it is not set<br>";
}
$variable = 5;
if (isset($variable)) {
	echo "step 2 - it is set<br/>";
}else{
	echo "step 2 - it is not set<br/>";
}
unset($variable);
if (isset($variable)) {
	echo "step 3 - it is set<br/>";
}else{
	echo "step 3 - it is not set<br/>";
} ?>
