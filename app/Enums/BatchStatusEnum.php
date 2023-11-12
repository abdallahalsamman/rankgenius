<?php

namespace App\Enums;

enum BatchStatusEnum : string
{
    case DONE = 'DONE';
    case CANCELLED = 'CANCELLED';
    case IN_PROGRESS = 'IN PROGRESS';
}
