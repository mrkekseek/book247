<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\MembershipController as Base;
use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\IframePermission;
use App\Paypal;
use App\Role;
use App\UserMembership;
use App\ShopResourceCategory;
use App\UserMembershipAction;
use App\UserMembershipInvoicePlanning;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\InvoiceItem;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Auth as AuthLocal;
use App\Http\Libraries\ApiAuth;

/*
 * This controller is linked to the User Membership Plan assigned to him. The actions here are linked to an active membership plan assigned to a user or a plan that will be assigned
 */
class MembershipController extends Base
{



}
