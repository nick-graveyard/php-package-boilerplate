<?php
namespace Rxmg\Esp\Maropost\Connector;
// Only require if running as standalone
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
use Rxmg\EspInterface\Interfaces\PhpEspConnectorInterface;
use GuzzleHttp\Client;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/1/18
 * Time: 10:26 AM
 */
class PhpConnector implements PhpEspConnectorInterface
{

    public $errors = [];
    public $acct;
    public $auth;
    public $format;
    public static $debug_guzzle = false;

    /**
     * PhpConnector constructor.
     * @param null $details
     */
    public function __construct($details = null)
    {
        if (!empty($details)) $this->configure($details);
    }

    /**
     * @param array $details
     * @return bool
     * @throws \Exception
     */
    public function configure(array $details): bool
    {
        $acct = $details['account'] ?? null;
        $auth = $details['auth'] ?? null;
        $format = $details['format'] ?? 'json';

        if (!$acct) {
            throw new \Exception("Maropost account ID is required. Get it from https://app.maropost.com");
        }

        if (!$auth) {
            throw new \Exception("Maropost auth token is required. Get it from https://app.maropost.com/accounts/{$acct}/connections");
        }

        // Set base properties
        $this->acct = $acct;
        $this->auth = $auth;
        $this->format = $format;
        return true;
    }

    /**
     * @param $e
     * @param null $url
     * @return null
     */
    protected function handleGuzzleErrors($e, $url = null)
    {
        if (!empty($response = $e->getResponse())) {
            $error['status_code'] = $response->getStatusCode();
            $error['message'] = $response->getBody()->getContents();
            $error['url'] = $url ?? null;
            return $error;
        }
        return null;
    }

    /**
     * @param string $api_target
     * @param array $json_data
     * @param string $format
     * @return mixed|null|\Psr\Http\Message\ResponseInterface
     * @throws \Throwable
     * http://api.maropost.com/accounts/1377/contacts.json?auth_token=e1RHLG0yyQUv12MYmAwkLbkq08-o6Y_zTCJMWuHZj3LxpPD322lqqQ
     */
    protected function post(string $api_target, array $json_data, string $format = "json"): array
    {
        $this->isConfigured();
        $this->errors = null;
        $url = "http://api.maropost.com/accounts/$this->acct/$api_target.$format?auth_token=$this->auth";
        $client = new Client();
        try {
            $response = $client->post($url, [
                'json' => $json_data,
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'debug' => self::$debug_guzzle,
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Throwable $e) {
            if (!empty($response = $this->handleGuzzleErrors($e, $url))) {
                $this->errors = $response;
                return $response;
            }
            throw $e;
        }
    }

    /**
     * @param string $api_target
     * @param array $data
     * @param string $format
     * @return mixed
     * @throws \Throwable
     */
    protected function get(string $api_target, array $data, string $format = "json"): array
    {
        $this->isConfigured();
        $params = [
            'auth_token' => $this->auth,
        ];
        $params = array_merge($params, $data);
        $url = "http://api.maropost.com/accounts/$this->acct/$api_target.$format";
        $client = new Client();
        try {
            $response = $client->get($url, ['query' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Throwable $e) {
            if (!empty($response = $this->handleGuzzleErrors($e, $url))) {
                $this->errors = $response;
                return $response;
            }
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function isConfigured()
    {
        if (empty($this->acct) || empty($this->auth)) {
            throw new \Exception('Account information is not configured!');
        }
    }

    /**
     * @param string $email
     * @param string $list_id
     * @param array|null $details
     * @return bool
     * @throws \Throwable
     */
    public function subscribeTo(string $email, string $list_id, array $details = null): bool
    {
        $details['email'] = $email;
        try {
            $response = $this->post("lists/$list_id/contacts", $details);
            if ($response) return true;
        } catch (\Throwable $e) {
            throw $e;
        }
        return false;
    }

    /**
     * Sample Url
     * http://api.maropost.com/accounts/1377/contacts/email.json?auth_token=this_is_test_token&contact[email]=nick.k@rxmg.com
     * @param string $email
     * @param string $list_id
     * @return bool
     */
    public function isSubscribed(string $email, string $list_id): bool
    {
        $contact = $this->get("contacts/email", ['contact[email]' => $email]);
        foreach ($contact["list_subscriptions"] as $list) {
            if ($list['list_id'] == $list_id) return true;
        }
        return false;
    }

    /**
     * @param string $email
     * @param string $list_id
     * @return bool
     */
    public function isDuplicate(string $email, string $list_id): bool
    {
        return $this->isSubscribed($email, $list_id);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}