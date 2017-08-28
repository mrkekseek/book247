<?php
namespace App\Http\Controllers\Federation;

use Illuminate\Http\Request;
use App\TempalteEmail;
use App\Http\Requests;
use App\Http\Controllers\EmailsController as Base;
use Auth;
use Validator;
use Activity;
use Response;
use DB;

class EmailsController extends Base {

    public function list_all()
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
        }

        $templates = array();

        foreach(TempalteEmail::where('is_default', 0)->get() as $template)
        {
            $templates[] = array(
                "id" => $template["id"],
                "title" => $template["title"],
                "hook" => $template["hook"],
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
            'table_head_text1' => 'Email Templates'
        ];

        $sidebar_link = 'admin-templates_email-list_all';

        return view('admin/templates_email/federation/list_all', array(
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
            return redirect()->intended(route('admin/login'));
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

        return view('admin/templates_email/federation/add', array(
            'breadcrumbs'  => $breadcrumbs,
            'text_parts'   => $text_parts,
            'in_sidebar'   => $sidebar_link,
            'variables'    => $this->variables,
            'country_list' => DB::table("countries")->orderBy("name", "asc")->get()
        ));
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
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

        return view('admin/templates_email/federation/edit', array(
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'template'      => $template = TempalteEmail::where('id', $request->id)->get()->first(),
            'template_id'   => $template->id,
            'country_list'  => DB::table("countries")->orderBy("name", "asc")->get(),
            'variables'     => $this->variables,
            'isset_default' => TempalteEmail::where("hook", TempalteEmail::where("id", $request->id)->first()->hook)->where("is_default", 1)->first() ? TRUE : FALSE
        ));
    }

}