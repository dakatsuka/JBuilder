<?php

namespace JBuilder\Tests;

use JBuilder\JBuilder;

class JBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testEncode_Hash()
    {
        $result = JBuilder::encode(function($json) {
            $json->name = "Dai";
            $json->age  = 30;
        });

        $this->assertEquals('{"name":"Dai","age":30}', $result);
    }

    public function testEncode_Array()
    {
        $result = JBuilder::encode(function($json) {
            $json->buildArray([1, 2, 3], function($json, $number) {
                $json->id = $number;
            });
        });

        $this->assertEquals('[{"id":1},{"id":2},{"id":3}]', $result);
    }

    public function testEncode_NestedArray()
    {
        $result = JBuilder::encode(function($json) {
            $json->name = "Dai";
            $json->age  = 30;
            $json->items(['item1', 'item2', 'item3'], function($json, $item) {
                $json->name = $item;
                $json->parts(['part1', 'part2'], function($json, $item) {
                    $json->name = $item;
                });
            });
        });

        $this->assertEquals('{"name":"Dai","age":30,"items":[{"name":"item1","parts":[{"name":"part1"},{"name":"part2"}]},{"name":"item2","parts":[{"name":"part1"},{"name":"part2"}]},{"name":"item3","parts":[{"name":"part1"},{"name":"part2"}]}]}', $result);
    }

    public function testEncode_NestedHash()
    {
        $result = JBuilder::encode(function($json) {
            $json->users(function($json) {
                $json->name = "Dai";
                $json->age  = 30;

                $json->address(function($json) {
                    $json->zipcode = "111-1111";
                    $json->street  = "Street";
                });
            });
        });

        $this->assertEquals('{"users":{"name":"Dai","age":30,"address":{"zipcode":"111-1111","street":"Street"}}}', $result);
    }

    public function testEncodeFromFile()
    {
        $result = JBuilder::encodeFromFile(dirname(__FILE__).'/views/users.json.php', array('name' => 'Dai'));
        $this->assertEquals('{"name":"Dai","age":30}', $result);
    }
}
