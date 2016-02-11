<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $primaryKey='id';

    public static $attributeNames = array(
        'name' => 'Role Name',
        'display_name' => 'Display Name',
        'description' => 'Role Description',
    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public function permission()
    {
        return $this->belongsToMany('App\Permission');
    }
    public function users()
    {
        return $this->belongsToMany('App\user');
    }
    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name' => 'required|min:3|max:50|unique:roles',
                    'display_name' => 'required|min:10',
                    'description' => '',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|min:3|max:50|unique:roles,'.($id ? "id, $id," : ''),
                    'display_name' => 'required|min:10',
                    'description' => '',
                ];
            }
            default:break;
        }
    }
}
