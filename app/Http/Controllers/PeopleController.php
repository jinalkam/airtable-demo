<?php

namespace App\Http\Controllers;
use Airtable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PeopleController extends Controller
{

	public function index()
	{ 
		$data = \Cache::remember('users', 30, function () {
			return Airtable::table('default')->all();
		});
		return view('people', ['users' => $data]);
	}



	public function store(Request $request)
	{ 

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 401);
		}

		$getResult = Airtable::table('default')->create([
		'Name' => $request->name,
		'Email' => $request->email,
		]);

		if($request->file('photo')) {
			 $file_url = cloudinary()->upload($request->file('photo')->getRealPath())->getSecurePath();
			 $getResult = Airtable::table('default')->patch($getResults['id'], ['Photo' => [['url' => $file_url]] ]);
			 
		}
        return response()->json([
			'message' => 'User added successfully',
			'user' => $getResult
		   ]);
	}
}
