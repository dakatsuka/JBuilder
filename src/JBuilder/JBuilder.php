<?php

namespace JBuilder;

/**
 * Class JBuilder
 *
 * @package JBuilder
 * @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class JBuilder
{
    /**
     * Encode JSON
     *
     * inline example:
     *
     *     $response = JBuilder::encode(function($json) {
     *         $json->name = "Dai";
     *         $json->age  = 30;
     *     });
     *
     *     echo $response;
     *     {"name": "Dai", "age": 30}
     *
     * @param callable $callback
     * @return string
     */
    public static function encode(\Closure $callback)
    {
        $json = new JSON();

        $callback->__invoke($json);

        return json_encode($json);
    }

    /**
     * Encode JSON from view file
     *
     * inline example:
     *
     *     echo JBuilder::encodeFromFile('/path/to/index.json.php');
     *
     * @param $file
     * @param array $parameters
     * @return string
     */
    public static function encodeFromFile($file, array $parameters = array())
    {
        $json = new JSON();

        $encoder = function($file) use ($parameters, $json) {
            extract($parameters);
            include $file;

            return $json;
        };

        return json_encode($encoder->__invoke($file));
    }
}
