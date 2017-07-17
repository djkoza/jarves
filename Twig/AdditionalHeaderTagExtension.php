<?php
/**
 * This file is part of Jarves.
 *
 * (c) Łukasz Kozikowski <djkoza@gmail.com>
 *
 *     J.A.R.V.E.S - Just A Rather Very Easy [content management] System.
 *
 *     http://jarves.io
 *
 * To get the full copyright and license information, please view the
 * LICENSE file, that was distributed with this source code.
 */

namespace Jarves\Twig;

use Jarves\PageStack;

class AdditionalHeaderTagExtension extends \Twig_Extension
{
    /**
     * @var PageStack
     */
    private $pageStack;

    /**
     * @param PageStack $pageStack
     */
    function __construct(PageStack $pageStack)
    {
        $this->pageStack = $pageStack;
    }

    public function getName()
    {
        return 'headertag';
    }

    public function getTokenParsers()
    {
        return array(new TokenParser\AdditionalHeaderTag($this->pageStack));
    }
}