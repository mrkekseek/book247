<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempalteEmail;
use App\Http\Requests;
use Auth;
use Validator;
use Activity;
use Response;

class EmailsController extends Controller
{
	public function list_all()
	{
		$templates = array();

        foreach(TempalteEmail::all() as $template)
        {
            $templates[] = array(
                "id" => $template["id"],
                "title" => $template["title"],
                "content" => $template["content"],
                "veriables" => $template["veriables"],
                "country" => $template["country_id"]
            );
        }

		$breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'Email templates',
            'subtitle'  => 'add/edit/view tempalte',
            'table_head_text1' => 'Email'
        ];

        $sidebar_link = 'admin-templates_email-list_all';

        return view('admin/templates_email/list_all', array(
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'templates'=> $templates
        ));
	}

    public function add()
    {
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'Email templates',
            'subtitle'  => 'add/edit/view tempalte',
            'table_head_text1' => 'Email'
        ];

        $sidebar_link = 'admin-templates_email-add';

        return view('admin/templates_email/add', array(
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link
        ));
    }

    public function store_email_template(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }

        $vars = $request->only('title', 'content', 'variables', 'hook', 'description');

        $fillable = [
            'title'  => $vars['title'],
            'content'  => $vars['content'],
            'variables'     => $vars['variables'],
            'hook'  => $vars['hook'],
            'description'   => $vars['description']
        ];

        $validator = Validator::make($fillable, TempalteEmail::rules('post'), TempalteEmail::$message, TempalteEmail::$attributeNames);
        if ($validator->fails())
        {
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try
        {
            $template = TempalteEmail::create($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'template_email',
                'action'        => 'Template Email',
                'description'   => 'Template Email profile created : ' . $template->id,
                'details'       => 'Created by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Template Email created. You can assign it to one of your locations',
                'title'   => 'Template Email Created'
            ];
        }
        catch (Exception $e)
        {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }


    public function update_email_template(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || !$user->is_back_user())
        {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }

        $vars = $request->only('title', 'content', 'variables', 'hook', 'description');

        $email_template = TempalteEmail::where('id', $request->id)->get()->first();

        $fillable = [
            'title'  => $vars['title'],
            'content'  => $vars['content'],
            'variables'     => $vars['variables'],
            'hook'  => $vars['hook'],
            'description'  => $vars['description']
        ];

        $validator = Validator::make($fillable, TempalteEmail::rules('post', $email_template->id), TempalteEmail::$message, TempalteEmail::$attributeNames);

        if ($validator->fails())
        {
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $email_template->update($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'email_template',
                'action'        => 'Email Template',
                'description'   => 'Email Template successfully updated : '.$email_template->id,
                'details'       => 'Updated by user : '.$user->id,
                'updated'       => true,
            ]);

            return [
                'success' => true,
                'message' => 'Email Template updated. This will have influence in the future',
                'title'   => 'Email Template Updated'
            ];
        }
        catch (Exception $e) {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

    public function edit(Request $request)
    {
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'Email templates',
            'subtitle'  => 'add/edit/view tempalte',
            'table_head_text1' => 'Email'
        ];

        $sidebar_link = 'admin-templates_email-edit';

        return view('admin/templates_email/edit', array(
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'template'    => $template = TempalteEmail::where('id', $request->id)->get()->first(),
            'template_id' => $template->id
        ));
    }

    public function delete_email_template(Request $request)
    {

    }
}
