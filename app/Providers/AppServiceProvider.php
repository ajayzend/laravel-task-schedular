<?php

namespace App\Providers;

use App\Task;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Observers\TaskObserver;
use App\Events\TaskExecutedEvent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Task::observe(TaskObserver::class);

        $this->app->resolving(Schedule::class, function($schedule){
           $this->schedule($schedule);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function schedule(Schedule $schedule){
         // fetch all active tasks
         $tasks = app('App\Task')->getActive();
         //$tasks = app('App\Task')->where('is_active', true)->get();
         //Schedule the tasks

         foreach($tasks as $task){
             $event = $schedule->exec($task->command);
             $event
                 ->cron($task->expression)
                 ->before(function() use ($event){
                     $event->start = microtime(true);
                 })
                 ->after(function() use ($event, $task){
                     $elapsed_time = microtime(true) - $event->start;
                     //event() // Helper function
                     event(new TaskExecutedEvent($task, $elapsed_time));
                    //  if(file_exists(storage_path('task-'.sha1($task->command.$task->expression)))){
                    //      $output = file_get_contents(storage_path('task-'.sha1($task->command.$task->expression)));
                    //      $task->results()->create([
                    //          'duration' => $elapsed_time * 1000,
                    //          'result' => $output
                    //      ]);
                    //      unlink(storage_path('task-'.sha1($task->command.$task->expression)));
                    //  }
                 })
                 ->sendOutputTo(storage_path('task-'.sha1($task->command.$task->expression)));

             if($task->dont_overlap){
                 $event->withoutOverlapping();
             }

             if($task->run_in_maintenance){
                 $event->evenInMaintenanceMode();
             }
         }
    }
}
