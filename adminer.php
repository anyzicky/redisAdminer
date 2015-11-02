<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
//redson for work with redis
//TODO: move to this main template php
include 'Redson.php';

$redis = new Redson\Redis();
$arKeys = $redis->keys('*'); //all keys in db

function name() {
	return 'redisAdminer';
}
function version() {
	return '0.1';
}

function debug_print(array $var){
	echo "<pre>".print_r($var)."</pre>";
}


function keyAdd() {
	$html = "<p><b>Add key</b></p><div claass='add-key'>";
	$html .= "<form action='' method='POST' >";
	//$html .= "<p><label for='type'>Type</label></p>";

	$html .= "<p><label for='key'>Key:</label><input type='text' name='newkey'></p>";
	$html .= "<p><label for='val'>Value:</label><textarea></textarea></p>";
	$html .= "<p><input type='submit' name='add' value='Add' /></p>";
	$html .= "</form>";
	$html .= "</div>";

	return $html;
}

function keyInfo($key) {
	global $redis;

	$html = "<p><b>View key</b></p><table>";
	$html .= "<thead><th>Type</th><th>Size</th><th>Value</th><th>Operations</th></thead><tbody>";
	if(strlen($key)){
		$val = $redis->get($key);
		
		$html .= "<tr><td>".$val."</td><td>".strlen($val)."</td><td>".$val."</td><td>";
		//operation
			$html .= "<a class='operation' href='?type=key&do=edit&key=".$key."'>edit</a>";
			$html .= "<a class='operation' href='?type=key&do=delete&key=".$key."'>delete</a>";
		
		$html .= "</td></tr>";
	}
	$html .= "</tbody></table>";
	return $html;
}
/**
 * TODO: set in main php file
 * @return [type] [description]
 */
function style() {
	echo "<link rel=\"stylesheet\" href=\"style.css\">";
}

function head($title) {
	$head = "<!DOCTYPE html><html><head>";
	$head .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	$head .= "<title>{$title}</title>";
	style();
	$head .= "</head>";
	echo $head;
}

/**
 * TODO: add helpfull buttons
 * [footer description]
 * @return [type] [description]1
 */
function footer() {
	$footer = "<footer id=\"footer\"><div class=\"innertube\">";
	$footer .= "<a href='?type=key&do=add'>[Add key]</a>";
	$footer .= "</div>";
	$footer .= "</footer>";
	echo $footer;
}

function endhead() {
	echo "</body></html>";
}

/**
 * TODO: add info about php and redis 
 * [contentHeader description]
 * @return [type] [description]
 */
function contentHeader() {
	$html = "<body><header id=\"header\"><div class=\"innertube\">";
	$html .= "<p>".name()." ".version()."</p>";
	$html .= "</div></header>";
	echo $html;
}

function leftMenu(array $keys) {
	if(is_array($keys)){
		$html = "<nav id=\"nav\"><div class=\"innertube\">";
		$html .= "<h1>Keys</h1><ul>";
		foreach($keys as $key){
			$html .= "<li><a href=\"?type=key&do=view&key=$key\">" . $key . "</a></li>";
		}
		$html .= "</ul></div></nav>";	

		echo $html;	
	}
}

//html this start
head('redisAdminer');
	$keys = $redis->keys('*');
	contentHeader();

	$contentHead = "<main>{$who}<div class=\"innertube\">";
	echo $contentHead;

	//page content
	switch ($_REQUEST['type']) {
		case 'key':
			if($_REQUEST['do'] == 'add')
				echo keyAdd();
			if($_REQUEST['do'] == 'view')
				echo keyInfo($_REQUEST['key']);
			break;
		
		default:
			# code...
			break;
	}

	$contentFooter = "</div></main>";
	echo $contentFooter;

	leftMenu($arKeys);

	footer();
			
endhead();
//html this end