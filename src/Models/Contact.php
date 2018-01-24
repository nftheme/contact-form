<?php

namespace Garung\ContactForm\Models;

use NF\Models\Model;

/**
 *
 */
class Contact extends Model
{
    const TYPE_CONTACT  = 'contact';
    const TYPE_SUBCRIBE = 'subcribe';
    const CANCEL        = 2;
    const ACTIVE        = 1;
    const DEACTIVE      = 0;
    /**
     * [$table_name name of table]
     * @var string
     */
    protected $table = PREFIX_TABLE . 'contact';

    /**
     * [$primary_id primary key of table]
     * @var string
     */
    protected $primary_key = 'id';

    protected $fillable = ['data', 'type', 'name_slug', 'status', 'created_at', 'updated_at'];

}
