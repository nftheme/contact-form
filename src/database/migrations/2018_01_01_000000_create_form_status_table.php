<?php

use Garung\Database\Connect\NFDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateFormStatusTable extends NFDatabase
{
    public $table = 'form_status';

    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (!Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->create($table_name, function($table){
                $table->increments('id');
                $table->integer('status_id');
                $table->string('form_name', 150)->comment('slug name of form');
                $table->string('name', 100);
                $table->boolean('is_default');
                $table->timestamps();
            });
        }       
    }

    public function down() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->drop($table_name);
        }
    }
}
