<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
//redson for work with redis
//TODO: move to this main template php
include 'Redson.php';

$redis = new Redson\Redis();
$keys = $redis->keys('*'); 


function name() {
	return 'redisAdminer';
}
function version() {
	return '0.1';
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
	$footer = "<footer id=\"footer\"><div class=\"innertube\"><p>Help buttons</p></div>";
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

function leftMenu() {

	$html = "<nav id=\"nav\"><div class=\"innertube\">";
	$html .= "<h1>Keys</h1><ul>";
	$html .= "<li><a href=\"#\">Link 1</a></li>";
	$html .= "<li><a href=\"#\">Link 2</a></li>";
	$html .= "<li><a href=\"#\">Link 3</a></li>";
	$html .= "</ul></div></nav>";	

	echo $html;	
}

//html this start
head('redisAdminer');
	
	contentHeader();

	$content = <<<CONTENT
				
		<main>{$who}
			<div class="innertube">
				
				<h1>Heading</h1>
				<p>table text</p>
				
			</div>
		</main>

		
CONTENT;
		echo $content;

		leftMenu();

		footer();
			
endhead();
//html this end