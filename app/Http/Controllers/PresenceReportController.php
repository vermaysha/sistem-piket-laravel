<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PresenceReportController extends Controller
{
    public function index(Request $req) {

        $query =  Presence::query();
        $month = request()->get('month');

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $today = Carbon::today();

        $data = [
            'minggu' => [
                1 => 'Pertama',
                2 => 'Kedua',
                3 => 'Ketiga',
                4 => 'Keempat',
            ],
            'hari' => [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu',
            ],
            'data' => $query->get(),
            'monthName' => $today->month($month)->monthName
        ];

        // return view('presence-report', $data);

        $pdf = Pdf::loadView('presence-report', $data);
        return $pdf->stream();
    }
}
