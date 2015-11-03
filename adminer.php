<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
//redson for work with redis
//TODO: move to this main template php
include 'Redson.php';

$redis = new Redson\Redis();
$arKeys = $redis->keys('*'); //all keys in db

/* start adminer functions*/

function name() {
	return 'redisAdminer';
}
function version() {
	return '0.1';
}

function debug_print(array $var){
	echo "<pre>".print_r($var)."</pre>";
}

/**
 * TODO: set in main php file
 * @return [type] [description]
 */
function style() {
	echo "<link rel=\"stylesheet\" href=\"style.css\">";
}

/* end adminer functions */

/* view functions */
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

function serverInfo(){
	global $redis;

	$arInfo = $redis->info('server');

	return $arInfo;
}

function console ($cmd = ''){

	global $redis;

	$html = "<p>Console</p><form action='' method='get'>";
	$html .= "<input type='hidden' name='type' value='console' />";
	$html .= "<p><textarea rows='10' cols='90' name='cmd'>";
	if(isset($_REQUEST['cmd'])) 
		$html .= $_REQUEST['cmd'];
	else
		$html .= 'info';
	$html .= "</textarea></p>";
	$html .= "<p><input type='submit' name='go' value='Execute'/></p>";
	$html .= "</form>";

	if(!empty($cmd)) {

		$result = $redis->$cmd();
		if(is_array($result)){
			$result = implode(' ',$result);
		}
		$html .= "<p>".nl2br($result)."</p>";
	}

	return $html;
}

/* end view functions */

/* start content functions */

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
	$footer .= "<a href='?type=console&do=view'>[Console]</a>";
	$footer .= "<a href='?type=info&do=view'>[Information]</a>";
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
	$html .= "<p><a href='" . $_SERVER['PHP_SELF'] . "'>" . name() . " " . version() . "</a></p>";
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

/* end content functions */

//html this start
head('redisAdminer');
	$keys = $redis->keys('*');
	contentHeader();

	$contentHead = "<main>{$who}<div class=\"innertube\">";
	echo $contentHead;

	//page content
	if(isset($_REQUEST['type'])){
		switch ($_REQUEST['type']) {
		case 'key':
			if($_REQUEST['do'] == 'add')
				echo keyAdd();
			if($_REQUEST['do'] == 'view')
				echo keyInfo($_REQUEST['key']);
			break;
		case 'info':
			echo nl2br(serverInfo());
			break;
		case 'console':
			$cmd = '';
			if(isset($_REQUEST['cmd'])) $cmd = $_REQUEST['cmd'];
			echo console($cmd);
			break;
		default:
			# code...
			break;
	}
	}

	$contentFooter = "</div></main>";
	echo $contentFooter;

	leftMenu($arKeys);

	footer();
			
endhead();
//html this end