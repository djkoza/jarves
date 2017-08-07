<?php
/**
 * This file is part of Jarves.
 *
 * (c) Marc J. Schmidt <marc@marcjschmidt.de>
 *
 *     J.A.R.V.E.S - Just A Rather Very Easy [content management] System.
 *
 *     http://jarves.io
 *
 * To get the full copyright and license information, please view the
 * LICENSE file, that was distributed with this source code.
 */

namespace Jarves\Configuration;

class ThemeLayout extends ThemeContent
{
    protected $rootName = 'layout';

	protected $attributes = ['key', 'doctype', 'staticPlugins'];

	protected $requiredProperties = ['key', 'file'];

    protected $elementMap = ['staticPlugin' => 'ThemeLayoutStaticPlugin'];

	/**
	 * A key which is saved in the database for further access of this layout.
	 *
	 * Note: The file path is not directly stored anywhere, instead only this key is.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Allows to overwrite the default docType of PageResponse::$docType.
	 *
	 * Default is Jarves:Doctypes:html5.html.twig (Jarves/Resources/views/Doctypes/html5.html.twig)
	 *
	 * You should copy and paste the default and adjust it accordingly.
	 *
	 * @var string
	 */
	protected $doctype;


    /**
     * A array with static plugins - called by Plugin Twig Extension
     *
     * Allows use plugin router
     *
     * @var array
     */
    protected $staticPlugins = array();

	/**
	 * @param mixed $key
	 */
	public function setKey( $key ) {
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getDoctype()
	{
		return $this->doctype;
	}

	/**
	 * @param string $doctype
	 */
	public function setDoctype($doctype)
	{
		$this->doctype = $doctype;
	}

    /**
     * @return array
     */
    public function getStaticPlugins()
    {
        return $this->staticPlugins;
    }

    /**
     * @param ThemeLayoutStaticPlugin[] $staticPlugins
     */
    public function setStaticPlugins(array $staticPlugins)
    {
        $this->staticPlugins = $staticPlugins;
    }
}