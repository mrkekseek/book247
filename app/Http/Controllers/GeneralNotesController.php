<?php

namespace App\Http\Controllers;
use App\BookingNote;
use Auth;

use App\GeneralNote;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class GeneralNotesController extends Controller
{
    public function create(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'You need to be logged in',
                'errors'  => 'You need to be logged in as an employee in order to use this function'];
        }

        $vars = $request->only('memberID','custom_message','private_message','title_message','privacy','note_type','note_action');
        $allGood = true;
        $returnMessage = '';

        $member = User::where('id','=',$vars['memberID'])->get()->first();
        if (!$member){
            return [
                'success' => false,
                'title'   => 'Member not found',
                'errors'  => 'The member you want to send this message to was not found in the system'];
        }

        if (isset($vars['note_action']) && $vars['note_action']==1){
            $privateStatus = 'pending';
        }
        else{
            $privateStatus = 'unread';
        }

        $note_fill = [
            'by_user_id'    => $user->id,
            'for_user_id'   => $member->id,
            'note_title'    => $vars['title_message'],
            'note_body'     => '',
            'note_type'     => isset($vars['note_type'])?$vars['note_type']:'general note',
            'privacy'       => '',
            'status'        => 'unread'];
        if (strlen($vars['custom_message'])){
            $note_fill['note_body'] = $vars['custom_message'];
            $note_fill['privacy']   = 'everyone';

            $validator = Validator::make($note_fill, GeneralNote::rules('POST'), GeneralNote::$message, GeneralNote::$attributeNames);
            if ($validator->fails()){
                $allGood = false;
                $returnMessage.='Error adding custom message; ';
            }
            else {
                $generalNote = GeneralNote::create($note_fill);
                $generalNote->save();
                $returnMessage.='Custom message successfully added. ';
            }
        }

        if ($vars['private_message']){
            $note_fill['note_body'] = $vars['private_message'];
            $note_fill['status'] = $privateStatus;
            $note_fill['privacy']   = isset($vars['privacy'])?$vars['privacy']:'employees';

            $validator = Validator::make($note_fill, GeneralNote::rules('POST'), GeneralNote::$message, GeneralNote::$attributeNames);
            if ($validator->fails()){
                $allGood = false;
                $returnMessage.='Error adding private message; ';
            }
            else {
                $generalNote = GeneralNote::create($note_fill);
                $generalNote->save();
                $returnMessage.='Private message successfully added. ';
            }
        }

        if ($allGood == true){
            return [
                'success'   => true,
                'title'     => 'Message/Messages added',
                'message'   => $returnMessage
            ];
        }
        else{
            return [
                'success'   => false,
                'title'     => 'Something went wrong',
                'errors'    => $returnMessage
            ];
        }
    }

    public function status_update(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'You need to be logged in',
                'errors'  => 'You need to be logged in as an employee in order to use this function'];
        }

        $note_status = ['cancel'=>'deleted', 'complete'=>'completed', 'read'=>'read'];
        $vars = $request->only('noteID','status','is_general');
        if ($vars['is_general']==1){
            $note = GeneralNote::where('id','=',$vars['noteID'])->whereIn('status',['pending','unread'])->get()->first();
        }
        else{
            $note = BookingNote::where('id','=',$vars['noteID'])->whereIn('status',['pending','unread'])->get()->first();
        }

        if ($note && isset($note_status[$vars['status']])){
            $note->status = $note_status[$vars['status']];
            $note->save();

            if ($vars['status']=="read"){
                $return_message = 'Message status updated to '.strtoupper($vars['status']);
            }
            else{
                $return_message = 'Message status updated to '.strtoupper($vars['status']).'. Page will reload.';
            }

            return [
                'success'   => true,
                'title'     => 'Message Status Updated',
                'message'   => $return_message
            ];
        }
        else{
            return [
                'success'   => false,
                'title'     => 'Something went wrong',
                'errors'    => 'Status update could not be processed. Reload the page and try again'
            ];
        }
    }
}
