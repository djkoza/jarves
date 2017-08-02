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

namespace Jarves\Model;

use Jarves\Model\Base\Content as BaseContent;

class Content extends BaseContent implements ContentInterface
{
    public function setType($v)
    {
        return parent::setType(strtolower($v));
    }

    public function getImageFileId()
    {
        $result = json_decode($this->content);

        if($this->type == 'image' && !empty($result->file)){
            return $result->file;
        }

        return null;
    }

}
