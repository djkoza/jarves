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

namespace Jarves\Twig\Node;

class AdditionalHeaderTag extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $body, $lineno, $tag = 'headertag')
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("echo '<!--[jarves-headertag]-->' . ob_get_clean() . '<!--[/jarves-headertag]-->';\n")
        ;
    }
}