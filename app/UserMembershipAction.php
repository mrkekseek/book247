<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMembershipAction extends Model
{
    protected $table = 'user_membership_actions';

    public static $attributeNames = array(
        'user_membership_id' => 'Membership ID',
        'action_type'   => 'Action Type',
        'start_date'    => 'Start Date',
        'end_date'  => 'End Date',
        'added_by'  => 'Added By',
        'notes'     => 'Notes',
        'processed' => 'Processed',
        'status'    => 'Status'
    );

    public static $message = array();

    protected $fillable = [
        'user_membership_id',
        'action_type',
        'start_date',
        'end_date',
        'added_by',
        'notes',
        'processed',
        'status'
    ];

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
                    'user_membership_id' => 'required|exists:user_memberships,id',
                    'action_type'   => 'required|in:freeze,cancel',
                    'start_date'    => 'required|date',
                    'end_date'  => 'required|date',
                    'added_by'  => 'required|exists:users,id',
                    'processed' => 'required|in:0,1',
                    'status'    => 'required|in:active,old,cancelled'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_membership_id' => 'required|exists:user_memberships,id',
                    'action_type'   => 'required|in:freeze,cancel',
                    'start_date'    => 'required|date',
                    'end_date'  => 'required|date',
                    'added_by'  => 'required|exists:users,id',
                    'processed' => 'required|in:0,1',
                    'status'    => 'required|in:active,old,cancelled'
                ];
            }
            default:break;
        }
    }

    public function add_note($noteText){
        $notes = json_decode($this->notes);
        $notes[] = $noteText;
        $this->notes = json_encode($notes);
        $this->save();
    }
}
