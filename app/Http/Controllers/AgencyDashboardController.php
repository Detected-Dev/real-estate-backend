<?php

namespace App\Http\Controllers;

use App\Models\PropertyRequest;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class AgencyDashboardController extends Controller
{
    public function index(Request $request){

        $agency = $request->user()->agency;
        $agencyProperties = $agency->properties()->with('visits')->get();
        $pendingVisits = PropertyVisit::where([['agency_id' , $agency->id],['status' , 'pending']])->with('property' , 'user')->get();
        $pendingRequests = PropertyRequest::where([['agency_id' , $agency->id] , ['status' , 'pending']])->with('property','user')->get();




        return response()->json([
            'agency' => $agency,
            'agencyProperties' => $agencyProperties,
            'agencyPendingVisits' => $pendingVisits,
            'agencyPendingRequests' => $pendingRequests,
        ]);
    }
}
