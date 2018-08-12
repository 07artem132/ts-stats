<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {
	function list() {
		$users = User::all();
		return view( 'UserList' ,['users'=>$users]);
	}
}
