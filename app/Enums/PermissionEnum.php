<?php
namespace App\Enums;


use Konekt\Enum\Enum;

class PermissionEnum extends  Enum {
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
}
