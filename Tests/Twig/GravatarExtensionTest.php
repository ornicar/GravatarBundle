<?php

namespace Ornicar\GravatarBundle\Tests\Twig;

use Ornicar\GravatarBundle\Templating\Helper\GravatarHelperInterface;
use Ornicar\GravatarBundle\Twig\GravatarExtension;

class GravatarExtensionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var GravatarHelperInterface
     */
    private $helper;

    /**
     * @var GravatarExtension
     */
    private $extension;

    public function setUp()
    {
        if (!class_exists('Twig_Extension')) {
            $this->markTestSkipped('Twig_Extension cannot be found');
        }

        $this->helper = $this->getMockBuilder('Ornicar\GravatarBundle\Templating\Helper\GravatarHelperInterface')->getMock();
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
        $this->extension->getProfileUrl('henrik@bjrnskov.dk');
        $this->extension->getProfileUrlForHash(md5('henrik@bjrnskov.dk'));
    }

    public function testName()
    {
        $this->assertEquals('ornicar_gravatar', $this->extension->getName());
    }

    public function testFunctions()
    {
        $this->assertContainsOnlyInstancesOf('\Twig_SimpleFunction', $this->extension->getFunctions());

        $expectedNames = array(
            'gravatar',
            'gravatar_hash',
            'gravatar_profile',
            'gravatar_profile_hash',
            'gravatar_exists',
        );

        $functions = $this->extension->getFunctions();
        foreach ($expectedNames as $n => $expectedName) {
            $this->assertSame($expectedName, $functions[$n]->getName());
        }
    }
}
