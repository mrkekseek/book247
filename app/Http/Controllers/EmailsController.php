<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempalteEmail;
use App\Http\Requests;
use Auth;
use Validator;
use Activity;
use Response;
use DB;

class EmailsController extends Controller
{
    var $variables = array(
        "id"        => "User id",
        "email"     => "Email",
        "firstname" => "First name",
        "lastname"  => "Last name"
    );

	public function list_all()
	{
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return redirect('/');
        }

		$templates = array();

        foreach(TempalteEmail::where('is_default', 0)->get() as $template)
        {
            $templates[] = array(
                "id" => $template["id"],
                "title" => $template["title"],
                "hooks" => DB::table("templates_hook")->where("template_id", $template["id"])->get(),
                "content" => $template["content"],
                "variables" => json_decode($template["variables"]),
                "country" => DB::table("countries")->where("id", $template["country_id"])->first()
            );
        }

		$breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'       => '',
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
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return redirect('/');
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

        $sidebar_link = 'admin-templates_email-add';

        return view('admin/templates_email/add', array(
            'breadcrumbs'  => $breadcrumbs,
            'text_parts'   => $text_parts,
            'in_sidebar'   => $sidebar_link,
            'variables'    => $this->variables,
            'country_list' => DB::table("countries")->get()
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

        $vars = $request->only('title', 'content', 'variables', 'hooks', 'country_id', 'description');

        $fillable = [
            'title'         => $vars['title'],
            'content'       => $vars['content'],
            'variables'     => json_encode(explode(",", $vars['variables'])),
            'description'   => $vars['description'],
            'country_id'    => $vars['country_id'],
            'is_default'    => 0
        ];

        $validator = Validator::make($fillable, TempalteEmail::rules('create'), TempalteEmail::$message, TempalteEmail::$attributeNames);
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
            foreach ($vars["hooks"] as $name)
            {
                DB::table("templates_hook")->insert(array("template_id" => $template->id, "name" => $name));
            }

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'template_email',
                'action'        => 'Template Email',
                'description'   => 'Template Email profile created : ' . $template->id,
                'details'       => 'Created by user : ' . $user->id,
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

    public function reset_default(Request $request)
    {
       $user = Auth::user();
        if ( ! $user || !$user->is_back_user())
        {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }

        $default_template = TempalteEmail::where('hook', TempalteEmail::where('id', $request->id)->first()->hook)->where("is_default", 1)->first();
        
        if ( ! $default_template)
        {
            return [
                'success' => false,
                'errors'  => 'Error not found default template',
                'title'   => 'Not found'];
        }

        $fillable = array(
            "title"       => $default_template->title,
            "content"     => $default_template->content,
            "country_id"  => $default_template->country_id,
            "description" => $default_template->description
        );

        $email_template = TempalteEmail::where('id', $request->id)->where('is_default', 0)->first();

        try {
            $email_template->update($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'email_template',
                'action'        => 'Email Template',
                'description'   => 'Email Template successfully reset default : ' . $email_template->id,
                'details'       => 'Updated by user : '.$user->id,
                'updated'       => true,
            ]);

            return [
                'success' => true,
                'message' => 'Email Template Reset. This will have influence in the future',
                'title'   => 'Email Template Reset'
            ];
        }
        catch (Exception $e) {
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

        $vars = $request->only('title', 'content', 'country_id', 'description');

        $email_template = TempalteEmail::where('id', $request->id)->get()->first();

        $fillable = [
            'title'  => $vars['title'],
            'content'  => $vars['content'],
            'country_id' => $vars['country_id'], 
            'description'  => $vars['description'],
            'variables'    => json_encode(explode(",", $request->variables))
        ];


        $validator = Validator::make($fillable, TempalteEmail::rules('update', $email_template->id), TempalteEmail::$message, TempalteEmail::$attributeNames);

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
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return redirect('/');
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

        $sidebar_link = 'admin-templates_email-edit';

        return view('admin/templates_email/edit', array(
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'template'      => $template = TempalteEmail::where('id', $request->id)->get()->first(),
            'template_id'   => $template->id,
            'country_list'  => DB::table("countries")->get(),
            'variables'    => $this->variables,
            'isset_default' => TempalteEmail::where("hook", TempalteEmail::where("id", $request->id)->first()->hook)->where("is_default", 1)->first() ? TRUE : FALSE
        ));
    }

    public function make_default(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || !$user->is_back_user())
        {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }

        $vars = TempalteEmail::where("id", $request->id)->get()->first();

        $fillable = [
            'title'         => $vars['title'],
            'content'       => $vars['content'],
            'variables'     => $vars['variables'],
            'hook'          => $vars['hook'],
            'description'   => $vars['description'],
            'country_id'    => $vars['country_id'],
            'is_default'    => 1
        ];

        $validator = Validator::make($fillable, TempalteEmail::rules('make_default'), TempalteEmail::$message, TempalteEmail::$attributeNames);
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
                'description'   => 'Template Email make default : ' . $template->id,
                'details'       => 'Created by user : ' . $user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Template Email make Default. You can assign it to one of your locations',
                'title'   => 'Template Email make Default'
            ];
        }
        catch (Exception $e)
        {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

    public function delete_email_template(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || !$user->is_back_user())
        {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }

        $email_template = TempalteEmail::where(array("id" => $request->id, "is_default" => 0))->first();
        $default_template = TempalteEmail::where(array("hook" => $email_template->hook, "is_default" => 1))->first();

        try
        {
            $email_template->delete();
            if ($default_template)
            {
                $default_template->delete();
            }

            return [
                'success' => true,
                'message' => 'Template Email delete You can assign it to one of your locations',
                'title'   => 'Template Email delete'
            ];
        }
        catch (Exception $e)
        {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }
}
