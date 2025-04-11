<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class Basic extends Controller
{
  public function index()
  {
    $users = User::all();
    return view('content.tables.tables-basic', compact('users'));
  // return dd($user);
  }
}
