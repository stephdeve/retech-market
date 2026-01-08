<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $allUsers = User::all();

        // Data for User Registration Chart (Last 6 months)
        $usersData = User::selectRaw('COUNT(*) as count, strftime("%Y-%m", created_at) as month')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Data for Product Creation Chart (Last 6 months)
        $productsData = \App\Models\Product::selectRaw('COUNT(*) as count, strftime("%Y-%m", created_at) as month')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Prepare labels (last 6 months) to ensure all months are present
        $labels = [];
        $userCounts = [];
        $productCounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->translatedFormat('F'); // Month name in French
            $userCounts[] = $usersData[$monthKey] ?? 0;
            $productCounts[] = $productsData[$monthKey] ?? 0;
        }

        return view('admin.dashboard', compact("allUsers", "labels", "userCounts", "productCounts"));
    }

    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    public function promoteToAdmin(User $user)
    {
        $user->assignRole('admin');
        return redirect()->back()->with('success', 'Utilisateur promu au rôle administrateur avec succès.');
    }

    public function demoteFromAdmin(User $user)
    {
        // Prevent admin from demoting themselves
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas rétrograder votre propre compte.');
        }

        $user->removeRole('admin');
        return redirect()->back()->with('success', 'Utilisateur rétrogradé du rôle administrateur avec succès.');
    }

    // see user's detail
    public function showUser(User $user)
    {
        return view('admin.user_detail', compact('user'));
    }
}

