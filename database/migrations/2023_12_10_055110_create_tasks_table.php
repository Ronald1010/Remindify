<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('deadline_date')->nullable();;
            $table->time('deadline_time')->nullable();;
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
       
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
}
