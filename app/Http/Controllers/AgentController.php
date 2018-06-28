<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\AgentRepository;

class AgentController extends BaseController
{
    protected $agentRepository;

    public function index(){
        return view('Agent.index');
    }

}
