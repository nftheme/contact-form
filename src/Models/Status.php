<?php

namespace Vicoders\ContactForm\Models;

use Vicoders\ContactForm\Models\Contact;
use NF\Models\Model;

/**
 *
 */
class Status extends Model
{
    /**
     * [$table_name name of table]
     * @var string
     */
    protected $table = PREFIX_TABLE . 'form_status';

    /**
     * [$primary_id primary key of table]
     * @var string
     */
    protected $primary_key = 'id';

    protected $fillable = ['form_name', 'status_id', 'name', 'is_default', 'created_at', 'updated_at'];

    public function contact() {
        return $this->hasMany(Contact::class, 'curr_status_id', 'status_id');
    }
}
