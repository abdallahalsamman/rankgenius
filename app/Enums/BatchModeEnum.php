<?php

namespace App\Enums;

enum BatchModeEnum: string
{
    case TOPIC = 'TOPIC';
    case TITLE = 'TITLE';
    case KEYWORD = 'KEYWORD';
}
