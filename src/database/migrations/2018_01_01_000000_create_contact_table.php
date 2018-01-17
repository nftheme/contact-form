<?php

use Garung\Database\Connect\NFDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateContactTable extends NFDatabase
{
    public $table = 'contact';

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
                $table->text('data');
                $table->string('type_of_name', 100);
                $table->boolean('status');
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
