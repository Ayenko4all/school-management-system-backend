<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends RespondsWithHttpStatusController
{
    public function index(){
        $usersCount = User::count();
        $teacherCount = Teacher::count();
        $classroomCount = Classroom::count();
        $termCount = Term::count();
        $subjectCount = subject::count();
        $sessionCount = session::count();

//        $studentCount = Student::count();

        return $this->respond([
            'userCount'    => $usersCount,
            'classroomCount' => $classroomCount,
            'teacherCount' => $teacherCount,
            'termCount' => $termCount,
            'subjectCount' => $subjectCount,
            'sessionCount' => $sessionCount,
        ]);
    }
}
