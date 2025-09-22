<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // pencarian
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $perPage = $request->get('perPage', 10);

        $users = $query->latest()->paginate($perPage)->withQueryString();

        // format tanggal
        $users->getCollection()->transform(function ($user) {
            $user->created_at = $user->created_at->format('d-m-Y');
            return $user;
        });

        return Inertia::render('users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'perPage']),
        ]);
    }
}
