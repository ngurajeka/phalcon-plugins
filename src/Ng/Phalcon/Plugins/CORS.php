<?php
/**
 * CORS Plugin
 *
 * PHP Version 5.4.x
 *
 * @category Library
 * @package  Library
 * @author   Ady Rahmat MA <adyrahmatma@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/ngurajeka/phalcon-plugins
 */
namespace Ng\Phalcon\Plugins;


use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * CORS Plugin
 *
 * @category Library
 * @package  Library
 * @author   Ady Rahmat MA <adyrahmatma@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/ngurajeka/phalcon-plugins
 */

class CORS extends Plugin
{
    protected $dev          = false;
    protected $origin       = '*';
    protected $whitelist    = array();

    public function __construct(array $whitelist, $dev=false)
    {
        $this->dev          = $dev;
        $this->whitelist    = $whitelist;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if (!$this->checkOrigin()) {
            $this->response->send();
            return false;
        }

        if ($this->request->isOptions()) {
            $this->setupOptions();
            return false;
        }

        return true;
    }

    protected function checkOrigin()
    {
        if ($this->dev === true) {
            return true;
        }

        $origin         = $this->request->getHeader("Origin");
        if (!in_array($origin, $this->whitelist)) {
            return false;
        }

        $this->origin   = $origin;
        return true;
    }

    protected function setupHeaders()
    {
        $this->response->setHeader("Access-Control-Allow-Credentials", 'true');
        $this->response->setHeader(
            "Access-Control-Allow-Headers",
            "Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization"
        );
        $this->response->setHeader(
            "Access-Control-Allow-Methods",
            "GET, PATCH, PUT, POST, DELETE, OPTIONS"
        );
        $this->response->setHeader("Access-Control-Allow-Origin", $this->origin);
        $this->response->setHeader(
            "Content-Type", "application/json; charset=UTF-8"
        );
    }

    protected function setupOptions()
    {
        $this->setupHeaders();

        $content            = array();
        $content["status"]  = 200;
        $content["message"] = "OK";

        $this->response->setJsonContent($content);

        $this->response->setStatusCode(200, "OK");
        $this->response->send();
    }
}