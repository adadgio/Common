<?php

namespace Adadgio\Common;

use Symfony\Component\HttpFoundation\Response;

/**
 * A custom easy response object handler to respond with JSON data
 */
class JsonResponse
{
    /**
     * Sends a default JSON response of whatever data passed here as array or object. Or any response.
     *
     * @param array|object Response data
     *
     * @param integer HTTP status code
     *
     * @param array Response headers
     *
     * @return object A modified Symfony\Component\HttpFoundation\Response
     */
    public static function fire($data, $code = 200, $headers = array())
    {
        $response = new Response();
        $response->setContent(json_encode($data));

        // default headers are JSON contents
        $response->headers->set('Content-Type', 'application/json');

        if (!empty($headers)) {
            foreach($headers as $header => $value) {
                $response->headers->set($header, $value);
            }
        }

        $response->setStatusCode($code);

        return $response;
    }

}
