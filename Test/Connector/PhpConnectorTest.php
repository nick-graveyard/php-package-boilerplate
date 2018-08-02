<?php
namespace Rxmg\Esp\Maropost\Test\Connector;
$approot = __DIR__ . "/../..";
require_once "$approot/vendor/autoload.php";
use Rxmg\Esp\Maropost\Connector\PhpConnector as MaropostPhpConnector;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use \Exception;

// Load .env variables
$dotenv = new Dotenv($approot);
$dotenv->load();

/**
 * Class PhpConnectorTest
 */
class PhpConnectorTest extends TestCase
{

    public $connector;

    protected function setUp()
    {
        $this->connector = new MaropostPhpConnector([
            'account' => getenv('MAROPOST_ACCT'),
            'auth' => getenv('MAROPOST_AUTH')
        ]);
    }

    public function testConfigure()
    {
        $maropost = new MaropostPhpConnector();
        $final = $maropost->configure(['auth' => 'testauth', 'account' => 'testaccount']);
        $this->assertTrue($final);

        $this->expectException(Exception::class);
        $maropost->configure([]);
    }

    public function testContactCreate()
    {
        $result = $this->connector->subscribeTo('nick.k@rxmg.com', '1', ['first_name' => 'nick2', 'last_name' => 'kiermaier']);
        $this->assertTrue($result == true);
    }

    public function testSubscribeTo()
    {
        $result = $this->connector->isSubscribed('nick.k@rxmg.com', 1);
        $this->assertTrue($result == true);
    }
}