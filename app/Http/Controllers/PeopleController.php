<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function view_user()
    {
        return view('admin.people.view_user');
    }

    public function add_user()
    {
        return view('admin.people.add_user');
    }
}
