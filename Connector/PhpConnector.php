<?php
namespace <<insertRootNameSpaceHere>>\Connector;

// Only require if running as standalone
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use GuzzleHttp\Client;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/1/18
 * Time: 10:26 AM
 */
class PhpConnector
{
    /**
     * PhpConnector constructor.
     * @param null $details
     */
    public function __construct($details = null)
    {
        // TBI
    }
}