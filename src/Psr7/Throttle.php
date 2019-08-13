<?php
/*
* Request throttling
*
* Copyright (c) 2017 Thomas Wormann <mail@thomaswormann.com>
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/mit-license.php
*
* Project home:
* https://github.com/thomaswormann/throttle
*
*/
namespace ThomasWormann\Throttle\Psr7;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \ThomasWormann\Throttle\Store\StoreInterface;

/*
 *
 */
class Throttle
{
    private $logger;

    private $store;

    private $maxRequests = 10;


    /**
     * Constructor
     *
     * @param StoreInterface    The store object
     * @param array             configuration settings
     * @param object            PSR-3 compatible logger implementation, e.g. Monolog
     */
    public function __construct(StoreInterface $store, $configuration = false, $logger = false )
    {
        $this->store = $store;
        $this->maxRequests = isset($configuration['maxRequests']) ? $configuration['maxRequests'] : $this->maxRequests;
    }


    /**
     * Invoke middleware
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        # When the store count is higher than the maxRequests configured,
        # instantly respond with a 429 (Too many requests)
        # TODO: Add RETRY-AFTER header
        if($this->store->count($request->getParam('email')) >= $this->maxRequests)
        {
            $newResponse = $response->withStatus(429);
        } else {
            # Otherwise create a new entry for the request
            $this->store->set($request->getParam('email'));
            $newResponse = $next($request, $response);
        }
        return $newResponse;
    }

}

