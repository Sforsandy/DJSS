<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
class OtherPagesController extends Controller {

    public function PageNameFunction() // 
    {
    	return view('other_pages/pagename'); // resources-> views-> other_pages ->pagename.blade.php
    }
    
}