<?php

namespace Ornicar\GravatarBundle\Tests\Twig;

use Ornicar\GravatarBundle\Twig\GravatarExtension;

class GravatarExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Twig_Extension')) {
            $this->markTestSkipped('Twig_Extension cannot be found');
        }

        $this->helper = $this->getMock('Ornicar\GravatarBundle\Templating\Helper\GravatarHelperInterface');
        $this->extension = new GravatarExtension($this->helper);
    }

    public function testProxyMethods()
    {
        $this->helper->expects($this->any())->method('getUrl');
        $this->helper->expects($this->any())->method('getUrlForHash');
        $this->helper->expects($this->any())->method('exists');

        $this->extension->getUrl('henrik@bjrnskov.dk');
        $this->extension->exists('henrik@bjrnskov.dk');
        $this->extension->getUrlForHash(md5('henrik@bjrnskov.dk'));
    }

    public function testName()
    {
        $this->assertEquals('ornicar_gravatar', $this->extension->getName());
    }

    public function testFunctions()
    {
        $this->assertContainsOnly('Twig_Function_Method', $this->extension->getFunctions());

        $this->assertEquals(array(
            'gravatar',
            'gravatar_hash',
            'gravatar_exists',
        ), array_keys($this->extension->getFunctions()));

    }
}
