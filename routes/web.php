<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\GerenteContaController;
use App\Http\Controllers\SolicitacaoLimiteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'spa')->name('home');

Route::view('/spa', 'spa')->name('spa');

Route::get('/admin', function () {
    return redirect()->route('clientes.index');
})->middleware('auth')->name('admin');

Route::get('/dashboard', function () {
    return redirect()->route('admin');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/contas/{id}/bloquear', [ContaController::class, 'bloquear'])->name('contas.bloquear');
    Route::post('/contas/{id}/desbloquear', [ContaController::class, 'desbloquear'])->name('contas.desbloquear');

    Route::get('/gerentes', [GerenteContaController::class, 'index'])->name('gerentes.index');
    Route::get('/gerentes/novo', [GerenteContaController::class, 'create'])->name('gerentes.create');
    Route::post('/gerentes', [GerenteContaController::class, 'store'])->name('gerentes.store');
    Route::get('/gerentes/{id}/editar', [GerenteContaController::class, 'edit'])->name('gerentes.edit');
    Route::post('/gerentes/{id}/atualizar', [GerenteContaController::class, 'update'])->name('gerentes.update');
    Route::post('/gerentes/{id}/remover', [GerenteContaController::class, 'destroy'])->name('gerentes.destroy');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/novo', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::post('/clientes/{id}/atualizar', [ClienteController::class, 'update'])->name('clientes.update');
    Route::post('/clientes/{id}/remover', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/clientes/{id}/extrato', [ClienteController::class, 'extrato'])->name('clientes.extrato');

    Route::get('/solicitacoes', [SolicitacaoLimiteController::class, 'index'])->name('solicitacoes.index');
    Route::post('/solicitacoes', [SolicitacaoLimiteController::class, 'store'])->name('solicitacoes.store');
    Route::post('/solicitacoes/{id}/aprovar', [SolicitacaoLimiteController::class, 'aprovar'])->name('solicitacoes.aprovar');
    Route::post('/solicitacoes/{id}/reprovar', [SolicitacaoLimiteController::class, 'reprovar'])->name('solicitacoes.reprovar');

    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

require __DIR__.'/auth.php';




