<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $usersWithZeroPosts = User::has('posts', '=', 0)->count();

        return response()->json([
            'Number of all users.' => $totalUsers,
            'Number of all posts' => $totalPosts,
            'Number of users with 0 posts.' => $usersWithZeroPosts,
        ]);
    }
}





