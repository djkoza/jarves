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

namespace Jarves\Twig\TokenParser;

use Jarves\PageStack;
use Jarves\Twig\Node\AdditionalHeaderTag as NodeAdditionalHeaderTag;

class AdditionalHeaderTag extends \Twig_TokenParser
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

    public function getTag()
    {
        return 'headertag';
    }

    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();

        $pageResopnse = $this->pageStack->getPageResponse();

        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideHeadertagEnd'), true);
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        $data = $body->getAttribute('data');
        if($data && $pageResopnse){
            $pageResopnse->addHeader($data);
        }

        return new NodeAdditionalHeaderTag($body, $lineno, $this->getTag());
    }

    public function decideHeadertagEnd(\Twig_Token $token)
    {
        return $token->test('endheadertag');
    }
}