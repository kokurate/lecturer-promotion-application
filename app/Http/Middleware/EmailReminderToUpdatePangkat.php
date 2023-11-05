<?php

namespace App\Http\Middleware;

use App\Mail\ReminderToUpdatePangkat;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailReminderToUpdatePangkat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $twelveMonthsAgo = Carbon::now()->subMonths(12);
        // $twelveMonthsAgo = Carbon::now()->subSecond(60);
        
        // Retrieve the data that meets the criteria from your model
        $users = User::where('level','dosen')
                    ->where('notified', false)
                    ->where('pengajuan_terakhir', '<', $twelveMonthsAgo)->get();

        // dd($data);

        foreach ($users as $item) {
            
            $item->update(['notified' => true]);

            $data = [
                'title' => 'PERINGATAN!!!',
                'sub_title' => 'Anda belum memperbarui pangkat Anda selama setahun.
                                Mohon untuk segera memperbarui pangkat anda',
                'latest' => 'Anda Terakhir Naik Pangkat Pada '. $item->pengajuan_terakhir, 
                'nama' =>  'Nama : '.$item->name,
                'pangkat' =>'Pangkat : '. $item->pangkat->pangkat,
                'jabatan' => 'Jabatan Fungsional : ' .$item->pangkat->jabatan_fungsional,
                'golongan' => 'Golongan : '.$item->pangkat->golongan,
                'url' => route('login'),
            ];

            Mail::to($item->email)->send(new ReminderToUpdatePangkat($data));
        }

        return $next($request);
    }
}
