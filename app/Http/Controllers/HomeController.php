<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function promotion_list()
    {
        return view('field.promotion.list');
    }
    public function add_promotion_list()
    {
        return view('field.promotion_add');
    }
    public function update_promotion_list()
    {
        return view('field.promotion_add');
    }
    public function schedule()
    {
        return view('field.schedule');
    }
    public function report()
    {
        return view('field.report');
    }
}
