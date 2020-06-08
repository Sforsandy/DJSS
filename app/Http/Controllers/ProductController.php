<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Response;

class ProductController extends Controller
{
    public function index(){
    	return view('product.index');
    }

    public function store(Request $request){
    	$data = new Product();
    	$data->name = $request['name'];
    	$data->Description = $request['description'];
    	$data->status = $request['status'];
    	$data->save();
    	echo "<PRE>";print_r($data->save());echo "</PRE>";die('end line');
    	return Response::json(array('success'=>1,'message'=>'inserted sucsessfully'));
    }
}
