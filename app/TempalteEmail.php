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
        'is_default',
        'country_id'
    );

    public static function rules($method)
    {
        switch ($method)
        {   
            case 'create':
            {
                return [
                    'title'  => 'required',
                    'variables'  => 'required',
                    'description'  => 'required',
                    //'hook'  => 'required|unique:email_templates',
                    'country_id' => 'required'
                ];
            }

            case 'update':
            {
                return [
                    'title'  => 'required',
                    'country_id'  => 'required',
                    'description'  => 'required'
                ];
            }

            case 'make_default' : {
                return [
                    'title'  => 'required',
                    'variables'  => 'required',
                    'description'  => 'required',
                    'country_id' => 'required'
                ];
            }

            default : break;
        }
    }
}
