<?php

namespace App\Http\Controllers;
use Airtable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PeopleController extends Controller
{

	public function index()
	{ 
		
		$data = \Cache::remember('users', 10, function () {
			return Airtable::table('default')->all();
		});
		return view('people', ['data' => $data]);
	}



}
