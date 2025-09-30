<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Streak;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CheckinController extends Controller
{
    public function index()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('home')->with('erro', 'Você precisa selecionar um usuário para continuar.');
        }

        return view('checkins.index', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('home')->with('erro', 'Você precisa selecionar um usuário para fazer check-in.');
        }

        $today = Carbon::today();

        // Apenas 1 check-in por dia
        $existingCheckin = Checkin::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingCheckin) {
            return redirect()->back()->with('erro', 'Você já fez check-in hoje.');
        }

        Checkin::create([
            'date' => $today,
            'type' => 'botao',
            'user_id' => $user->id,
        ]);

        // Atualiza streak individual
        $streak = Streak::firstOrCreate(
            ['user_id' => $user->id, 'group_id' => null],
            ['current_streak' => 0, 'longest_streak' => 0, 'last_checkin_date' => null]
        );

        $yesterday = Carbon::yesterday();
        $streak->current_streak = ($streak->last_checkin_date == $yesterday->toDateString()) 
            ? $streak->current_streak + 1 
            : 1;

        $streak->longest_streak = max($streak->longest_streak, $streak->current_streak);
        $streak->last_checkin_date = $today;
        $streak->save();

        return redirect()->back()->with('sucesso', "Check-in feito para {$user->name}!");
    }
}
