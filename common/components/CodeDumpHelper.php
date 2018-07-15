<?php
/**
 * Alternative to Var_dump. Creates a dump in easy readable format with colors & background
 * define it as a component in @app\config\main/php
 * e.g. use as \Yii::$app->yiidump->dump($variable) after defining it in frontend\config\main.php file;
 * @param $var for dumping.
 */

namespace common\components;

class CodeDumpHelper{


public static function dump() {
	$args = func_get_args();
	$var = array_shift($args);
	
	echo '<div style="background-color: #333;color: white;font-family: \'Trebuchet MS\';font-size: 16px; margin: 15px auto;-webkit-font-smoothing: antialiased;">';
	self::dump_line_content($var);
	
	if (is_array($var))
		self::dump_array($var, 1);
	else if (is_object($var))
		self::dump_array(get_object_vars($var), 1);
		
	echo '</div>';
	
	if (count($args))
		foreach ($args as $arg)
			self::dump($arg);
}

protected static function dump_array($arr, $level) {
	$keys = array_keys($arr);
	foreach ($keys as $key) {
		$var = $arr[$key];
		self::dump_line_content($var, $key, $level);
		if (gettype($var) == 'object')
			$var = get_object_vars($var);
		if (gettype($var) == 'array')
			self::dump_array($var, $level + 1);
	}
}

protected static function dump_line_content($var, $key = null, $level = null) {
	echo '<div style="padding: 8px;' . (is_null($level) ? '' : 'padding-left: ' . (8 + $level * 25) . 'px;') . '">';
	if (gettype($var) == 'string')
		echo '<table style="color: white;"><td style="vertical-align: top;">';
	if (!is_null($key))
		echo '[' . (gettype($key) == 'string' ? "'" . $key . "'" : $key) . '] => ';
	switch (gettype($var)) {
		case 'array':
			echo '<span style="color: #ff27cd;">' . gettype($var) . '</span>(' . count($var) . ')';
			break;
		case 'string':
			echo '<span style="color: #ffbc1b;">' . gettype($var) . '</span>(' . strlen($var) . ') : </td><td>"' . htmlspecialchars($var) . '"</td></table>';
			break;
		case 'boolean':
			echo '<span style="color: #00e3ff;">' . gettype($var) . '</span> : ' . ($var ? 'true' : 'false');
			break;
		case 'NULL':
			echo '<span style="color: #DD00A2;">' . gettype($var) . '</span> : ' . ($var ? 'true' : 'false');
			break;
		case 'object':
			echo '<span style="color: #F7F7F7;">' . gettype($var) . '</span> : ' . get_class($var);
			break;
		default:
			echo '<span style="color: #95DB00;">' . gettype($var) . '</span> : ' . $var;
	}
	echo '</div>';
	}
}