<?php

namespace App\Http\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\Poke;
use App\Models\User;
use MailchimpMarketing\ApiClient;
use Mailjet\Client;
use \Mailjet\Resources;

class PokeController
{
    public Poke $poke;
    public static array $response;

    public function __construct() {
        $this->poke = new Poke();
        self::$response['error'] = 0;
        self::$response['conf'] = 0;
    }
    public static function poke(Request $request) {
        if (!isset($_SESSION['user_id'])) {
            require_once VIEW_ROOT . '403.php';
            return;
        }

        $user = (new User())->getUserById($_SESSION['user_id']);

        $id = $request->getApiRequestData();
        if (!$id) {
            self::$response['error'] = 1;
            self::sendApiResponse();
            return;
        }
        $canPoke = (new self)->canPoke($_SESSION['user_id'], $id);
        $userPoked = (new User())->getUserById($id);
        if (!$canPoke) {
            self::$response['error'] = 1;
            self::sendApiResponse();
            return;
        }

        $create = (new self)->poke->create($_SESSION['user_id'], $id);
        if($create) {
            self::fireEmail($user->email, $user->name, $userPoked->email, $userPoked->name);
            self::$response['conf'] = 1;
            self::sendApiResponse();
            return;
        }
    }

    public function canPoke(int $from, int $to): bool
    {
        return $this->poke->canPoke($from, $to);
    }

    public static function fireEmail(string $senderEmail, string $senderName, string $recipientEmail, string $recipientName) {
        $mj = new \Mailjet\Client($_ENV['MAIL_API_KEY'], $_ENV['MAIL_API_SECRET'],true,['version' => 'v3.1']);
        $body = self::getEmailBody($senderEmail, $senderName, $recipientEmail, $recipientName);
        $response = $mj->post(Resources::$Email, ['body' => $body]);
//        @todo: pretty error msg examples
/*        highlight_string("<?php\n\$response =\n" . var_export($response, true) . ";\n?>");die();*/
//        echo '<pre>' . var_export($response, true) . '</pre>';die();
    }

    public static function getEmailBody(string $senderEmail, string $senderName, string $recipientEmail, string $recipientName) {
        return [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'gedwidas@gmail.com',
                        'Name' => 'Gedvidas'
                    ],
                    'To' => [
                        [
                            'Email' => $recipientEmail,
                            'Name' => $recipientName
                        ]
                    ],
                    'Subject' => "You have been poked!",
                    'HTMLPart' => "Hello, our user " . $recipientName . "!. You have been poked on site: " . $_ENV['SITE_NAME'] . " by user: " . $senderName . " his email adress is: " . $senderEmail,
                ]
            ]
        ];
    }

    public static function sendApiResponse() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: POST, GET ');
        header('Content-Type: application/json; charset=UTF-8');

        http_response_code(200);
        echo json_encode([
            'response' => self::$response
        ]);
    }
}