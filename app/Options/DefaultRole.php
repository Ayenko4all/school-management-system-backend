<?php

namespace App\Options;

use App\Traits\ClassContant;

class DefaultRole {
    use ClassContant;

    public const SUPERADMIN    = 'super-admin';

    public const ADMIN         = 'admin';

    public const STUDENT       = 'student';

    public const TEACHER       = 'teacher';

    public const PARENT        = 'parent';

    public const USER          = 'user';

}
