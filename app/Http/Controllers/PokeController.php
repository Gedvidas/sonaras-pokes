<?php

namespace App\Http\Controllers;

use App\Models\Poke;
use function Composer\Autoload\includeFile;

class PokeController
{
    public Poke $poke;
    public static array $response;

    public function __construct() {
        $this->poke = new Poke();
        self::$response['error'] = 0;
        self::$response['conf'] = 0;
    }
    public static function poke() {
        $id = json_decode(file_get_contents('php://input', true));
        if ($id) {
            $id = (array) $id;
            if (isset($id['id'])) {
                $id = $id['id'];
            }
        }

        $canPoke = (new self)->canPoke($_SESSION['user_id'], $id);
        if ($canPoke) {
            if((new self)->poke->create($_SESSION['user_id'], $id)) {
                self::$response['conf'] = 1;
            }

        } else {
            self::$response['error'] = 1;
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: POST, GET ');
        header('Content-Type: application/json; charset=UTF-8');

        http_response_code(200);
        echo json_encode([
            'response' => self::$response
        ]);
    }

    public function canPoke(int $from, int $to){
        return $this->poke->canPoke($from, $to);
    }
}