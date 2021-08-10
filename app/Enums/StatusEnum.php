<?php
namespace App\Enums;


use Konekt\Enum\Enum;

class StatusEnum extends  Enum {
    const ACTIVE        = 'active';
    const INACTIVE      = 'inactive';
    const PENDING       = 'pending';
}
