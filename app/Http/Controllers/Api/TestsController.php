<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    public function test(){
        $user = User::query()->first();

        return $this->success($user);
    }
}
