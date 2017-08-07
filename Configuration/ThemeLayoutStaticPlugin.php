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

namespace Jarves\Configuration;

class ThemeLayoutStaticPlugin extends Model
{
    protected $rootName = 'staticPlugin';
    protected $attributes = ['pluginKey', 'pluginBundle'];

    protected $requiredProperties = ['pluginKey', 'pluginBundle'];

    /**
     * @var string
     */
    protected $pluginKey;

    /**
     * @var string
     */
    protected $pluginBundle;

    /**
     * @param string $pluginBundle
     */
    public function setPluginBundle($pluginBundle)
    {
        $this->pluginBundle = $pluginBundle;
    }

    /**
     * @param string $pluginKey
     */
    public function setPluginKey($pluginKey)
    {
        $this->pluginKey = $pluginKey;
    }

    public function getPluginKey()
    {
        return $this->pluginKey;
    }

    public function getPluginBundle()
    {
        return $this->pluginBundle;
    }
}