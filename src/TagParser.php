<?php

namespace App;

class TagParser
{
    public function parse($tags): array
    {
        return preg_split('/ ?[,|] ?/', $tags);
    }
}
