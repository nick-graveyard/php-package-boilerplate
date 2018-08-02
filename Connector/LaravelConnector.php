<?php
namespace Rxmg\Esp\Maripost\Connector;

use Rxmg\EspInterface\Interfaces\EspConnectorInterface;
use Rxmg\Esp\Maropost\Connector\PhpConnector;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/1/18
 * Time: 10:26 AM
 */
class LaravelConnector
{
    public $php_connector;

    /**
     * LaravelConnector constructor.
     */
    public function __construct()
    {
        $this->php_connector = new PhpConnector();
    }

    /**
     * @param Collection $details
     * @return bool
     */
    public function configure(Collection $details): bool
    {
        return $this->php_connector->configure($details->toArray());
    }

    /**
     * @param string $email
     * @param string $list_id
     * @param Collection|null $details
     * @return bool
     */
    public function subscribeTo(string $email, string $list_id, Collection $details = null): bool
    {
        return $this->php_connector->subscribeTo($email, $list_id, $details->toArray());
    }

    /**
     * @param string $email
     * @param string $list_id
     * @return bool
     */
    public function isSubscribed(string $email, string $list_id): bool
    {
        return $this->php_connector->isSubscribed($email, $list_id);
    }

    /**
     * @param string $email
     * @param string $list_id
     * @return bool
     */
    public function isDuplicate(string $email, string $list_id): bool
    {
        return $this->php_connector->isDuplicate($email, $list_id);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->php_connector->errors();
    }
}