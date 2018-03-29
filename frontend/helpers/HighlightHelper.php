<?php

namespace frontend\helpers;

class HighlightHelper
{

    public static function process($keyword, $content)
    {
        //\S - любой непробельный символ, i - нечувствительность к регистру символов
        return preg_replace('~(\S*' . $keyword . '\S*)~i', '<b>$0</b>', $content);
    }

}
