<?php

namespace App\Http\Controllers\Api;

use App\Models\AccessLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AccessLogsController extends Controller
{
    /**
     * List all User's access logs.
     *
     * @response array{data: AccessLog[]}
     */
    public function index()
    {
        $logs = AccessLog::all();

        return response()->json($logs);
    }

    /**
     * Create Access Log from id route param.
     *
     * @response {data: AccessLog} | {errors : object}
     */
    public function create(Request $request, string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                /** @var object*/
                'errors' => $validator->errors()
            ], 400);
        }

        $log = new AccessLog;
        $now = Carbon::now();
        $log->start = $now;
        $log->finish = $now->add(5, 'hour');
        $log->user_id = $id;
        $log->save();

        return response()->json(['data' => $log], 201);
    }

    /**
     * List Access Logs from user id route param.
     *
     * @response array{data: AccessLog[]} | {errors : object}
     */
    public function find(Request $request, string $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                /** @var object*/
                'errors' => $validator->errors()
            ], 400);
        }

        $logs = AccessLog::where('user_id', $id)->get();

        return response()->json(['data' => $logs]);
    }
}
