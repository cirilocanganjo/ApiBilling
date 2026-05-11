<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
     public string $apiBaseUrl;
     public $users = [];
     public $searcher;
     public $name;
     public $email;
     public $phone;
     public int $active_user_id = 0;
    public function __construct ()
    {
        $this->apiBaseUrl = config('services.api.base_url');
        $this->users = $this->GetActiveUsers();
    }


    public function GetUserReport () {
        return view('reports.users.index')->with([
            'users' => $this->users
        ]);
    }

    public function GetActiveUsers()
{
    try {
        $response = Http::timeout(10)
            ->acceptJson()
            ->withToken(session('access_token'))
            ->get("{$this->apiBaseUrl}/users");

        if ($response->failed()) {
            return collect();
        }

        $data = $response->json('data', []);
        return collect($data)->where('status', 'active');

    } catch (\Throwable $th) {
        return collect();
    }
}


public function GetUserReportDataByFilter(Request $request)
{

    try {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token')
        ])->get("{$this->apiBaseUrl}/users-reports", [
            'searcher' => $request->searcher,
            'active_user_id' => $request->active_user_id,
        ]);

        $data = collect($response->json('data', []));

        if ($data->isEmpty()) {
            return response()->view(
                'reports.users.partials.empty',
                [],
                200
            );
        }

        $pdf = Pdf::loadView(
            'reports.users.partials.report-pdf',
            ['data' => $data]
        )->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-utilizadores.pdf');

    } catch (\Throwable $th) {
         report($th);
        return response()->view(
            'reports.users.partials.empty',
            [

            ],
            200

        );
    }
}


}
