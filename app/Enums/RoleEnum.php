<?php
namespace App\Enums;


use Konekt\Enum\Enum;

class RoleEnum extends  Enum {

    const SITEADMIN = 'site-admin';
    const SCHOOLOWNER ='school-owner';
    const SUPERADMIN = 'super-admin';
    const STUDENT = 'student';
    const TEACHER = 'teacher';
    const PARENT = 'parent';
    const USER = 'user';
}
