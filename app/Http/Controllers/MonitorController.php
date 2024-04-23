<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\MonitorJoiner;

class MonitorController extends Controller
{
    use MonitorJoiner;
    /**
     * @throws Exception
     */
    public function join($monitor_hash)
    {
        if(Auth::check()){
            try {
                $monitor = $this->join_monitor($monitor_hash);
                return redirect('/monitors/' . $monitor->id);
            } catch (Exception $e) {
                return redirect('/join');
            }
        }else{
            return redirect('/register');
        }
    }
}
