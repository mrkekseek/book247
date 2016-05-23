<?php namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
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

    public static $attributeNames = array(
        'name' => 'Permission Name',
        'display_name' => 'Display Name',
        'description' => 'Permission Description',
    );

    public function roles()
    {
        return $this->belongsToMany('App\Role');
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
                    'name' => 'required|min:5|max:50|unique:permissions',
                    'display_name' => 'required|min:5',
                    'description' => 'required|min:10',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|min:5|max:50|unique:permissions,name'.($id ? ",$id,id" : ''),
                    'display_name' => 'required|min:5',
                    'description' => 'required|min:10',
                ];
            }
            default:break;
        }
    }
}