<?php

namespace App\Enums;

enum BatchModeEnum: string
{
    case CONTEXT = 'CONTEXT';
    case TITLE = 'TITLE';
    case KEYWORD = 'KEYWORD';
}
