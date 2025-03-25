<?php

namespace App;

enum BusinessType: string
{
    case RETAIL = 'Retail';
    case F_AND_B = 'F&B';
    case SERVICES = 'Services';
    case EDUCATION = 'Education';
    case HEALTHCARE = 'Healthcare';
}
