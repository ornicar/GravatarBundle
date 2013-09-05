<?php

namespace Ornicar\GravatarBundle\Tests\Templating\Helper;

use Ornicar\GravatarBundle\GravatarApi;
use Ornicar\GravatarBundle\Templating\Helper\GravatarHelper;

class GravatarHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $this->helper = new GravatarHelper(new GravatarApi());
    }

    public function testGetUrlReturnsTheCorrectUrl()
    {
        $this->assertEquals('http://www.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=80&r=g', $this->helper->getUrl('henrik@bearwoods.dk'));
    }

    public function testGetUrlReturnsTheCorrectSecureUrl()
    {
        $this->assertEquals(
            'https://secure.gravatar.com/avatar/0aa61df8e35327ac3b3bc666525e0bee?s=80&r=g',
            $this->helper->getUrl('henrik@bearwoods.dk', null, null, null, true)
        );
    }

    public function testCheckForAvatarExistance()
    {
        $this->assertTrue($this->helper->exists('henrik@bjrnskov.dk'));
        $this->assertFalse($this->helper->exists('someone@someonefake.lol'));
    }
}
