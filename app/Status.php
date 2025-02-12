<?php

namespace App;

enum Status: string
{
    case DONE = 'done';
    case MAINTENANCE = 'maintenance';
    case OFFSITE = 'offsite';
}
