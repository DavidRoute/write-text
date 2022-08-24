<?php

use Illuminate\Support\Facades\Route;
use App\Models\Policy;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $file = public_path('files/sql-result.csv');
    $data =  csv_to_array($file);

    collect($data)->each(fn ($row) => Policy::create($row));

    return view('welcome');
});
