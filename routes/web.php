<?php
use App\Events\OrderStatusUpdate;
use App\Events\TaskCreated;
use App\Task;
use App\Project;
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
    return view('welcome');
});

// Route::get('/tasks', function(){
// 	return Task::latest()->pluck('body');
// });

// Route::post('/tasks', function(){
// 	$task = Task::forceCreate(request(['body']));
// 	event(new TaskCreated($task));

// });

Route::get('/projects/{project}', function (App\Project $project){
	$project->load('tasks');
	// dd($project);
	return view('project.show',array("project" => $project));
});

Route::get('/api/projects/{project}', function (Project $project){
	return $project->tasks->pluck('body');
});

Route::post('/api/projects/{project}/tasks', function (Project $project){
	$task = $project->tasks()->create(request(['body']));
	event(new TaskCreated($task));
	return $task;
});

// Route::get('update', function(){
// 	OrderStatusUpdate::dispatch(new Order(1));

// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
