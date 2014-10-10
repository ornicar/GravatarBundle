<?php

namespace Ornicar\GravatarBundle\Tests;


use Ornicar\GravatarBundle\GravatarApi;
use Ornicar\GravatarBundle\GravatarImage;

class GravatarApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGravatarUrlWithDefaultOptions()
    {
        $api = new GravatarApi();
        $this->assertEquals('http://www.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=80&r=g', $api->getUrl('henrik@bearwoods.dk   '));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $router = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Router\RouterMock');
        $router->method('generate')->willReturn('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/0');

        $api->setCache($cache);
        $api->setRouter($router);

        $this->assertEquals('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/0', $api->getUrl('henrik@bearwoods.dk   '));
    }

    public function testGravatarSecureUrlWithDefaultOptions()
    {
        $api = new GravatarApi();
        $this->assertEquals('https://secure.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=80&r=g', $api->getUrl('henrik@bearwoods.dk', null, null, null, true));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $router = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Router\RouterMock');
        $router->method('generate')->willReturn('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/1');

        $api->setCache($cache);
        $api->setRouter($router);

        $this->assertEquals('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/1', $api->getUrl('henrik@bearwoods.dk', null, null, null, true));
    }

    public function testGravatarUrlWithDefaultImage()
    {
        $api = new GravatarApi();
        $this->assertEquals('http://www.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=80&r=g&d=mm', $api->getUrl('henrik@bearwoods.dk', 80, 'g', 'mm'));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $router = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Router\RouterMock');
        $router->method('generate')->willReturn('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/0');

        $api->setCache($cache);
        $api->setRouter($router);

        $this->assertEquals('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/80/g/mm/0', $api->getUrl('henrik@bearwoods.dk', 80, 'g', 'mm'));
    }

    public function testGravatarInitializedWithOptions()
    {
        $api = new GravatarApi(array(
            'size' => 20,
            'default' => 'mm',
        ));

        $this->assertEquals('http://www.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=20&r=g&d=mm', $api->getUrl('henrik@bearwoods.dk'));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $router = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Router\RouterMock');
        $router->method('generate')->willReturn('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/20/g/mm/0');

        $api->setCache($cache);
        $api->setRouter($router);

        $this->assertEquals('http://localhost/gravatar/image/0aa61df8e35327ac3b3bc666525e0bee/20/g/mm/0', $api->getUrl('henrik@bearwoods.dk'));
    }

    public function testGravatarExists()
    {
        $api = new GravatarApi();

        $this->assertFalse($api->exists('somefake@email.com'));

        $this->assertTrue($api->exists('henrik@bjrnskov.dk'));
    }

    public function testFetchImageNotCached()
    {
        $api = $this->getMock('Ornicar\GravatarBundle\GravatarApi', array('getClient'));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $cache->method('contains')->willReturn(false);

        $api->setCache($cache);

        $client = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Client\ClientMock');
        $response = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Client\ResponseMock');
        $body = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Client\ResponseBodyMock');

        $body->method('getContents')->willReturn('This is some content');
        $body->method('getSize')->willReturn(50);
        $response->method('getBody')->willReturn($body);
        $client->method('get')->willReturn($response);
        $api->method('getClient')->willReturn($client);

        /** @var GravatarImage $image */
        $image = $api->fetchImage('0aa61df8e35327ac3b3bc666525e0bee', 80, 'g', 'mm', 0);

        $this->assertEquals('This is some content', $image->getContent());
        $this->assertEquals(50, $image->getSize());
        $this->assertEquals('image/jpeg', $image->getType());
    }

    public function testFetchImageCached()
    {
        $api = $this->getMock('Ornicar\GravatarBundle\GravatarApi', array('getClient'));

        $cache = $this->getMock('Ornicar\GravatarBundle\Tests\Mock\Cache\CacheMock');
        $cache->method('contains')->willReturn(true);

        $gravatarImage = new GravatarImage(
            'This is some content',
            50,
            'image/jpeg'
        );

        $cache->method('fetch')->willReturn(serialize($gravatarImage));

        $api->setCache($cache);

        /** @var GravatarImage $image */
        $image = $api->fetchImage('0aa61df8e35327ac3b3bc666525e0bee', 80, 'g', 'mm', 0);

        $this->assertEquals('This is some content', $image->getContent());
        $this->assertEquals(50, $image->getSize());
        $this->assertEquals('image/jpeg', $image->getType());
    }
}
