<?php

namespace App\Http\Controllers;
use Airtable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PeopleController extends Controller
{

	public function index()
	{ 
		// No need to call Airtable API everytime someone req page
		// We are clearing cache when someone adds data
		$data = \Cache::rememberForever('users', function () {
			return Airtable::table('default')->all(500000);
		});
		return view('people', ['users' => $data]);
	}


	public function store(Request $request)
	{ 

		$getResult = Airtable::table('default')->create([
			'Name' => $request->name,
			'Email' => $request->email,
		]);
         
		if($request->file('photo')) {
			// upload file to cloudinary server
			 $file_url = cloudinary()->upload($request->file('photo')->getRealPath())->getSecurePath();
			 $getResult = Airtable::table('default')->patch($getResult['id'], ['Photo' => [['url' => $file_url]] ]);
			 
		}
		// Refresh Cache only if someone adds new data otherwise serve from cache
		\Cache::forget('users');
        return response()->json([
			'user' => $getResult
		   ]);
	}
}
