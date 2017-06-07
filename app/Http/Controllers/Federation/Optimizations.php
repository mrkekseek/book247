<?php

namespace App\Http\Controllers;

use App\OptimizeSearchMembers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class Optimizations extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function make_search_member_table_optimization(){
        //$user = Auth::user();
        //if (!$user || !$user->is_back_user()) {
        //    return ['success'   => false];
        //}

        $searchMembers = new OptimizeSearchMembers();
        $searchMembers->clean_rebuild_table();
    }

    public function add_new_members_to_table(){
        //$user = Auth::user();
        //if (!$user || !$user->is_back_user()) {
        //    return ['success'   => false];
        //}

        $searchMembers = new OptimizeSearchMembers();
        $searchMembers->add_missing_members();
    }
}
