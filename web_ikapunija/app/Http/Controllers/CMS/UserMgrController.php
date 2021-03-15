<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UserMgrController extends Controller
{
    public function index(Request $request)
    {
        session_start();
        if(!isset($_SESSION['email']))
        { return redirect('/login'); }

        $username = $_SESSION['nama'];
        return view('cms/user_manager', compact('username'));
    }
}
