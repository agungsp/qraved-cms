<?php

namespace App\Exports;

use App\Models\QravedUser;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QravedUserExport implements FromView
{
    public function view(): View
    {
        return view('exports.qraved_user', [
            'users' => QravedUser::all()
        ]);
    }
}
