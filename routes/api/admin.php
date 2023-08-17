<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\SignupFeeController;
use App\Http\Controllers\Api\Admin\TerminalProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\RegionController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\ChargeController;
use App\Http\Controllers\Api\Admin\ChargeRuleController;
use App\Http\Controllers\Api\Admin\AgentController;
use App\Http\Controllers\Api\Admin\AuditLogController;
use App\Http\Controllers\Api\Admin\BroadcastController;
use App\Http\Controllers\Api\Admin\CommissionController;
use App\Http\Controllers\Api\Admin\DeviceController;
use App\Http\Controllers\Api\Admin\TerminalIdController;
use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Controllers\Api\Admin\TicketController;
use App\Http\Controllers\Api\Admin\WalletActivityController;

Route::post('/login', [AuthController::class, 'login'])
    ->name('admin.login');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('admin.forgot-password');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('admin.reset-password');

Route::group(['middleware' => ['auth:admin']], function () {

    Route::group(['prefix' => 'dashboard', 'middleware' => 'can:stats.view'], function () {
        Route::get('/balances', [DashboardController::class, 'getBalances'])
            ->name('dashboard.balances');

        Route::get('/transactions-stats', [DashboardController::class, 'getTransactionStats'])
            ->name('dashboard.get-transaction-stats');

        Route::get('/agent-stats', [DashboardController::class, 'getAgentStats'])
            ->name('dashboard.get-agent-stats');
    });

    Route::get('/permissions', [RoleController::class, 'getPermissions'])
        ->name('admin.permissions');

    Route::get('/profile', [AuthController::class, 'profile'])
        ->name('user.profile');

    Route::post('/change-password', [AuthController::class, 'changePassword'])
        ->name('user.change-password');

    Route::group(['prefix' => 'roles', 'middleware' => ['can:roles.index']], function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware(['can:roles.index'])
            ->name('roles.index');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware(['can:roles.create'])
            ->name('roles.create');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware(['can:roles.update'])
            ->name('roles.update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware(['can:roles.delete'])
            ->name('roles.delete');
    });

    Route::group(['prefix' => 'agents', 'middleware' => ['can:agents.index']], function () {
        Route::get('/', [AgentController::class, 'index'])
            ->middleware(['can:agents.index'])
            ->name('agents.index');

        Route::get('/signups', [AgentController::class, 'signups'])
            ->middleware(['can:agents.index', 'can:agents.signup'])
            ->name('agents.signup');

        Route::get('/select', [AgentController::class, 'selection'])
            ->middleware(['can:agents.index'])
            ->name('agents.select');

        Route::post('/approve/{agent}', [AgentController::class, 'approve'])
            ->middleware(['can:agents.index', 'can:agents.approve'])
            ->name('agents.approve');

        Route::put('/{agent}', [AgentController::class, 'update'])
            ->middleware(['can:agents.update'])
            ->name('agents.update');

        Route::put('/enable/{agent}', [AgentController::class, 'enable'])
            ->middleware(['can:agents.enable'])
            ->name('agents.enable');

        Route::put('/disable/{agent}', [AgentController::class, 'disable'])
            ->middleware(['can:agents.disable'])
            ->name('agents.disable');

        Route::post('/reject/{agent}', [AgentController::class, 'reject'])
            ->middleware(['can:agents.index', 'can:agents.reject'])
            ->name('agents.reject');

        Route::get('/{agent}/device-orders', [SignupFeeController::class, 'getAgentSignups'])
            ->middleware(['can:agents.index'])
            ->name('agents.device-orders');

        Route::get('/{agent}/wallet/activity', [WalletActivityController::class, 'index'])
            ->name('agents.wallet-activity');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware(['can:users.index'])
            ->name('users.index');
        Route::post('/', [UserController::class, 'store'])
            ->middleware(['can:users.create'])
            ->name('users.create');
        Route::put('/{user}', [UserController::class, 'update'])
            ->middleware(['can:users.update'])
            ->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware(['can:users.delete'])
            ->name('users.delete');
    });

    Route::group(['prefix' => 'regions'], function () {
        Route::get('/', [RegionController::class, 'index'])
            ->middleware(['can:regions.index'])
            ->name('regions.index');

        Route::post('/', [RegionController::class, 'store'])
            ->middleware(['can:regions.create'])
            ->name('regions.create');

        Route::post('/', [RegionController::class, 'store'])
            ->middleware(['can:regions.create'])
            ->name('regions.create');

        Route::put('/{region}', [RegionController::class, 'update'])
            ->middleware(['can:regions.edit'])
            ->name('regions.update');

        Route::delete('/{region}', [RegionController::class, 'destroy'])
            ->middleware(['can:regions.delete'])
            ->name('regions.delete');
    });

    Route::group(['prefix' => 'charges'], function () {
        Route::get('/', [ChargeController::class, 'index'])
            ->middleware(['can:charges.index'])
            ->name('charges.index');

        Route::get('/{charge}', [ChargeController::class, 'show'])
            ->middleware(['can:charges.view'])
            ->name('charges.view');

        Route::post('/', [ChargeController::class, 'store'])
            ->middleware(['can:charges.create'])
            ->name('charges.create');

        Route::put('/{charge}', [ChargeController::class, 'update'])
            ->middleware(['can:charges.update'])
            ->name('charges.edit');

        Route::delete('/{charge}', [ChargeController::class, 'destroy'])
            ->middleware(['can:charges.delete'])
            ->name('charges.delete');

        Route::post('/{charge}/rules', [ChargeRuleController::class, 'store'])
            ->middleware(['can:charges.rules.create'])
            ->name('charges.rules.create');

        Route::get('/{charge}/rules', [ChargeRuleController::class, 'index'])
            ->middleware(['can:charges.rules.index'])
            ->name('charges.rules.index');

        Route::get('/{charge}/rules/{rule}', [ChargeRuleController::class, 'show'])
            ->middleware(['can:charges.rules.view'])
            ->name('charges.rules.view');

        Route::delete('/{charge}/rules/{rule}', [ChargeRuleController::class, 'destroy'])
            ->middleware(['can:charges.rules.delete'])
            ->name('charges.rules.delete');
    });

    Route::group(['prefix' => 'devices'], function () {
        Route::get('/', [DeviceController::class, 'index'])
            ->middleware(['can:devices.index'])
            ->name('devices.index');

        Route::post('/', [DeviceController::class, 'store'])
            ->middleware(['can:devices.create'])
            ->name('devices.create');

        Route::post('/upload', [DeviceController::class, 'upload'])
            ->middleware(['can:devices.upload'])
            ->name('devices.upload');

        Route::get('/{device}', [DeviceController::class, 'show'])
            ->middleware(['can:devices.view'])
            ->name('devices.view');

        Route::put('/{device}', [DeviceController::class, 'update'])
            ->middleware(['can:devices.update'])
            ->name('devices.update');

        Route::put('/{device}/tid/assign', [DeviceController::class, 'assignTid'])
            ->middleware(['can:devices.terminalId.assign'])
            ->name('devices.terminalId.assign');

        Route::put('/{device}/tid/unassign', [DeviceController::class, 'unassignTid'])
            ->middleware(['can:devices.terminalId.unassign'])
            ->name('devices.terminalId.unassign');

        Route::delete('/{device}', [DeviceController::class, 'destroy'])
            ->middleware(['can:devices.delete'])
            ->name('devices.delete');
    });

    Route::group(['prefix' => 'terminals'], function () {
        Route::get('/', [TerminalIdController::class, 'index'])
            ->middleware(['can:terminals.index'])
            ->name('terminals.index');

        Route::post('/', [TerminalIdController::class, 'store'])
            ->middleware(['can:terminals.create'])
            ->name('terminals.create');

        Route::post('/upload', [TerminalIdController::class, 'upload'])
            ->middleware(['can:terminals.upload'])
            ->name('terminals.upload');

        Route::get('/{terminalId}', [TerminalIdController::class, 'show'])
            ->middleware(['can:terminals.view'])
            ->name('terminals.view');

        Route::put('/{terminalId}', [TerminalIdController::class, 'update'])
            ->middleware(['can:terminals.update'])
            ->name('terminals.update');

        Route::delete('/{terminalId}', [TerminalIdController::class, 'destroy'])
            ->middleware(['can:terminals.delete'])
            ->name('terminals.delete');
    });

    Route::group(['prefix' => 'terminal-profiles'], function () {

        Route::get('/', [TerminalProfileController::class, 'index'])
            ->middleware(['can:terminal-profiles.index'])
            ->name('terminal-profiles.index');

        Route::post('/', [TerminalProfileController::class, 'store'])
            ->middleware(['can:terminal-profiles.create'])
            ->name('terminal-profiles.create');

        Route::put('/{terminalProfile}', [TerminalProfileController::class, 'update'])
            ->middleware(['can:terminal-profiles.update'])
            ->name('terminal-profiles.update');

        Route::delete('/{terminalId}', [TerminalIdController::class, 'destroy'])
            ->middleware(['can:terminal-profiles.delete'])
            ->name('terminals-profiles.delete');
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', [TransactionController::class, 'index'])
            ->middleware(['can:transactions.index'])
            ->name('transactions.index');

        Route::get('/{agent}', [TransactionController::class, 'getAgentTransactions'])
            ->middleware(['can:transactions.agent'])
            ->name('transactions.agent');

        Route::post('/{agent}/filter-by-date', [TransactionController::class, 'filterByDate'])
            ->name('agents.transactions.filter-by-date');
    });

    Route::group(['prefix' => 'tickets'], function () {
        Route::get('/', [TicketController::class, 'index'])
            ->middleware(['can:tickets.index'])
            ->name('tickets.index');

        Route::put('/{ticket}', [TicketController::class, 'update'])
            ->middleware(['can:tickets.update'])
            ->name('tickets.update');

        Route::put('/{ticket}/close', [TicketController::class, 'closeTicket'])
            ->middleware(['can:tickets.update'])
            ->name('tickets.update');

        Route::post('/{ticket}/messages', [TicketController::class, 'sendMessage'])
            ->middleware(['can:tickets.message'])
            ->name('tickets.message');

        Route::get('/{ticket}/messages', [TicketController::class, 'getMessages'])
            ->middleware(['can:tickets.messages.index'])
            ->name('tickets.message.index');
    });

    Route::get('/{user}/activity', [AuditLogController::class, 'index'])
        ->middleware(['can:activity.index'])
        ->name('activity.index');

    Route::group(['prefix' => 'signup-fees'], function () {
        Route::get('/', [SignupFeeController::class, 'index'])
            ->middleware(['can:signup_fees.index'])
            ->name('signup-fees.index');

        Route::post('/', [SignupFeeController::class, 'store'])
            ->middleware(['can:signup_fees.create'])
            ->name('signup-fees.create');

        Route::put('/{signupFee}', [SignupFeeController::class, 'update'])
            ->middleware(['can:signup-fees.update'])
            ->name('signup_fees.update');

        Route::delete('/{signupFee}', [SignupFeeController::class, 'destroy'])
            ->middleware(['can:signup_fees.delete'])
            ->name('signup-fees.delete');
    });

    Route::group(['prefix' => 'broadcasts'], function () {
        Route::get('/', [BroadcastController::class, 'index'])
            ->middleware(['can:broadcasts.index'])
            ->name('broadcasts.index');

        Route::post('/send', [BroadcastController::class, 'send'])
            ->middleware(['can:broadcasts.send'])
            ->name('broadcasts.send');
    });

    Route::group(['prefix' => 'commissions'], function () {
        Route::get('/', [CommissionController::class, 'index'])
            ->middleware(['can:commissions.index'])
            ->name('commissions.index');

        Route::post('/', [CommissionController::class, 'store'])
            ->middleware(['can:commissions.create'])
            ->name('commissions.create');

        Route::get('/types', [CommissionController::class, 'commissionTypes'])
            ->middleware(['can:commissions.types.view'])
            ->name('commissions.types');

        Route::get('/{commission}', [CommissionController::class, 'show'])
            ->middleware(['can:commissions.view'])
            ->name('commissions.view');

        Route::put('/{commission}', [CommissionController::class, 'update'])
            ->middleware(['can:commissions.edit'])
            ->name('commissions.edit');

        Route::delete('/{commission}', [CommissionController::class, 'destroy'])
            ->middleware(['can:commissions.delete'])
            ->name('commissions.delete');
    });
});
