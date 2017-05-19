<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempalteEmail extends Model
{
    protected $table = 'email_templates';

    protected $primaryKey = 'id';

    public static $message = array();

    public static $attributeNames = array(
		'title'  => 'Title',
		'content'  => 'Content',
		'variables'     => 'Cariables',
		'description'  => 'Description',
		'hook' => 'Hook',
		'country_id'    => 'Country'
    );

    protected $fillable = array(
        'title',
        'content',
        'variables',
        'description',
        'hook',
    );

    public static function rules($method)
    {
        switch ($method)
        {   
            case 'post':
            {
                return [
                    'title'  => 'required|min:5',
                    'content'  => 'required|min:3',
                    'variables'  => 'required|min:3',
                    'description'  => 'required|min:20',
                    'hook'  => 'required|min:3'
                ];
            }

            default : break;
        }
    }
}
