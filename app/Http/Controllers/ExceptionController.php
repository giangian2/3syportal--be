<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\UserDidNotMatchSubmission;

class ExceptionController extends Controller
{
    public function index(Request $request){
        throw new UserDidNotMatchSubmission("The user did not match the given submission id");
    }
}
