<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Checkin;
use App\Models\Streak;
use Illuminate\Http\Request;
use Carbon\Carbon;


class CheckinController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('checkins.index', compact('users'));
    }

     public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $today = Carbon::today();

        // só 1 checkin por dia
        $existingCheckin = Checkin::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingCheckin) {
            return redirect()->back()->with('erro', 'Usuário já fez check-in hoje.');
        }

        // Cria o checkin
        Checkin::create([
            'date' => $today,
            'type' => 'botao',
            'user_id' => $user->id,
        ]);

        // Atualiza streak individual
        $streak = Streak::firstOrCreate(
            ['user_id' => $user->id, 'group_id' => null], // streak individual
            ['current_streak' => 0, 'longest_streak' => 0, 'last_checkin_date' => null]
        );

        $yesterday = Carbon::yesterday();
        if ($streak->last_checkin_date == $yesterday->toDateString()) {
            $streak->current_streak += 1;
        } else {
            $streak->current_streak = 1; // reinicia
        }

        $streak->longest_streak = max($streak->longest_streak, $streak->current_streak);
        $streak->last_checkin_date = $today;
        $streak->save();

        return redirect()->back()->with('sucesso', "Check-in feito para {$user->name}!");
    }

}
