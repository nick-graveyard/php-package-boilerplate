<?php
namespace <<insertNameSpaceHere>>\Test\Connector;
$approot = __DIR__ . "/../..";
require_once "$approot/vendor/autoload.php";
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
        //TBI
    }

    public function testConfigure()
    {
      // TBI
    }

    public function testContactCreate()
    {
        // TBI
    }

    public function testSubscribeTo()
    {
       // TBI
    }
}