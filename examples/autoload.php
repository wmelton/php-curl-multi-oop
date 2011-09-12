<?php
/**
 * Auto load function to laod classes
 * @param unknown_type $className
 */
function __autoload($class_name) {

	require_once '../classes/' . str_replace('_', '/', $class_name) . '.php';
}
