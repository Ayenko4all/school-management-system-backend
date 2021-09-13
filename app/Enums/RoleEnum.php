<?php
namespace App\Enums;


use Konekt\Enum\Enum;

class RoleEnum extends  Enum {
    const SUPERADMIN    = 'super-admin';
    const ADMIN         = 'admin';
    const STUDENT       = 'student';
    const TEACHER       = 'teacher';
    const PARENT        = 'parent';
    const USER          = 'user';
}
