<?php
/**
 * This file is part of Jarves.
 *
 * (c) Lukasz M. Kozikowski <djkoza@gmail.com>
 *
 *     J.A.R.V.E.S - Just A Rather Very Easy [content management] System.
 *
 *     http://jarves.io
 *
 * To get the full copyright and license information, please view the
 * LICENSE file, that was distributed with this source code.
 */

namespace Jarves\Twig;

use Jarves\Model\Content;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'plugin';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('plugin', [$this, 'plugin'], array(
                'is_safe' => ['html'],
            ))
        );
    }

    public function plugin($data)
    {
        $content = new Content();

        $content->setType('plugin');
        $content->setContent($data);

        return $this->container->get('jarves.content.render')->renderContent($content);
    }

}