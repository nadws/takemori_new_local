<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user',$id_user)
        ->where('id_menu', 4)->first();
        if(empty($id_menu)) {
            return back();
        } else {
            $data = [
                'title' => 'Data Driver',
                'logout' => $request->session()->get('logout'),
                'driver' => Driver::all(),
            ];
    
            return view('driver.driver', $data);
        }
    }

    public function printDriver()
    {
        $data = [
            'title' => 'Driver Prtint',
            'date' => date('m/d/Y'),
            'driver' => Driver::all()
        ];
          
        $pdf = PDF::loadView('driver.pdf', $data);
    
        return $pdf->download('Data Driver.pdf');
    }
}
