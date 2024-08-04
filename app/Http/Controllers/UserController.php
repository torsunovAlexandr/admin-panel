<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(Request $request): Paginator
    {
        $perPage = $request->input('per_page', 10);

        return User::paginate($perPage);
    }
}
