<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Checkin;
use App\Models\Streak;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckinController extends Controller
{
    /**
     * Mostra a tela de check-in do usuário logado
     */
    public function index()
    {
        $userId = session('logged_user_id');

        if (!$userId) {
            return redirect()
                ->route('users.index')
                ->with('erro', 'Você precisa selecionar um usuário para continuar.');
        }

        $user = User::findOrFail($userId);

        return view('checkins.index', compact('user'));
    }

    /**
     * Registra o check-in do usuário logado
     */
    public function store(Request $request)
    {
        $userId = session('logged_user_id');

        if (!$userId) {
            return redirect()
                ->route('users.index')
                ->with('erro', 'Você precisa estar logado para fazer check-in.');
        }

        $user = User::findOrFail($userId);
        $today = Carbon::today();

        // Garantir apenas 1 check-in por dia
        $existingCheckin = Checkin::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingCheckin) {
            return redirect()->back()->with('erro', 'Você já fez check-in hoje.');
        }

        // Cria o check-in
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
            $streak->current_streak = 1; // reinicia streak
        }

        $streak->longest_streak = max($streak->longest_streak, $streak->current_streak);
        $streak->last_checkin_date = $today;
        $streak->save();

        return redirect()->back()->with('sucesso', "Check-in feito para {$user->name}!");
    }
}
