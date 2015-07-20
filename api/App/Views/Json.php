<?php
namespace App\Views;

class Json {
	public function render($content) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($content);
        return true;
    }
}