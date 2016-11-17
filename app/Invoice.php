<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App\InvoiceItems;
use Illuminate\Support\Facades\Config;

class Invoice extends Model
{
    protected $table = 'invoices';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'employee_id'   => 'Creator ID',
        'invoice_type'  => 'Invoice Type',
        'invoice_reference_id'  => 'Reference ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status'
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'employee_id',
        'invoice_type',
        'invoice_reference_id',
        'invoice_number',
        'other_details',
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
                    'user_id'               => 'required|exists:users,id',
                    'employee_id'           => 'required|exists:users,id',
                    'invoice_type'          => 'required|min:3',
                    'invoice_reference_id'  => 'required|numeric',
                    'invoice_number'        => 'required|unique:invoices,invoice_number',
                    'status'                => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'               => 'required|exists:users,id',
                    'employee_id'           => 'required|exists:users,id',
                    'invoice_type'          => 'required|min:3',
                    'invoice_reference_id'  => 'required|numeric',
                    'invoice_number'        => 'required|unique:invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'                => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            default:break;
        }
    }

    public function items(){
        return $this->hasMany('App\InvoiceItem', 'invoice_id', 'id')->orderBy('invoice_items.created_at','asc');
    }

    public function transactions(){
        return $this->hasMany('App\InvoiceFinancialTransaction', 'invoice_id', 'id')->orderBy('invoice_financial_transactions.created_at','asc');
    }

    public static function next_invoice_number(){
        $invoice = Invoice::select('invoice_number')->orderBy('invoice_number', 'desc')->get()->first();
        if ($invoice){
            return ($invoice->invoice_number+1);
        }
        else{
            return 1100;
        }
    }

    /**
     * @param $fillable [
     *      item_name : item name that will be shown on the invoice items list
     *      item_type : type of the item : membership payment, coupon code, discounts, bookings, products
     *      item_reference_id : the ID from the table
     *      quantity : number of items
     *      price : base price
     *      vat : VAT for the product group
     *      discount : applied discount
     * ]
     * @return array
     */
    public function add_invoice_item($item){
        //$vat = VatRate::orderBy('id','asc')->get()->first();
        //$vat_value = $vat->value;
        $vat_value = $item['vat'];
        $price_one_no_vat = $item['price'] - (($item['price']*$item['discount'])/100);
        $total_price = ($price_one_no_vat  + (($price_one_no_vat*$vat_value)/100)) * $item['quantity'];

        $fillable = [
            'invoice_id'    => $this->id,
            'item_name'     => $item['item_name'],
            'item_type'     => $item['item_type'],
            'item_reference_id' => $item['item_reference_id'],
            'quantity'      => $item['quantity'],
            'price'         => $item['price'],
            'vat'           => $vat_value,
            'discount'      => $item['discount'],
            'total_price'   => $total_price
        ];

        $validator = Validator::make($fillable, InvoiceItem::rules('POST'), InvoiceItem::$message, InvoiceItem::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_invoice_item = InvoiceItem::create($fillable);

            if ($the_invoice_item){
                return ['success' => true, 'message' => 'Item Added'];
            }
            else{
                return ['success' => false, 'errors' => 'Error adding item'];
            }
        }
        catch (Exception $e) {
            return ['success' => false, 'errors' => 'Error while adding item'];
        }
    }

    public function get_invoice_total(){
        $invoiceItems = InvoiceItem::where('invoice_id','=',$this->id)->get();
        if ($invoiceItems){
            $total = [
                'vat'       => [],
                'total_vat'      => 0,
                'total_discount' => 0,
                'total_price'    => 0,
                'total_sum'      => 0,
            ];
            foreach($invoiceItems as $item){
                $tdisc = (($item->discount * $item->price) / 100) * $item->quantity;
                $total['total_discount'] += $tdisc;

                $tprice = $item->price * $item->quantity;
                $total['total_price'] += $tprice;

                $tvat = (($tprice - $tdisc) * $item->vat) / 100;
                $total['total_vat'] += $tvat;
                if (isset($total['vat'][$item->vat])){
                    $total['vat'][$item->vat] += $tvat;
                }
                else{
                    $total['vat'][$item->vat] = $tvat;
                }

                $total['total_sum'] += ($tprice - $tdisc) + $tvat;
            }

            return $total;
        }
        else{
            return 0;
        }
    }

    public function add_transaction($user_id, $transaction_type, $status, $otherDetails, $id_partial_payment = false, $invoice_items = []){
        // not all invoice items are paid
        if ($id_partial_payment){
            $transactionAmount = 0;
            $invoiceItems = [];
            foreach($invoice_items as $item){
                $transactionAmount+=$item->total_price;
                $invoiceItems[] = $item->id;
            }
            $invoiceItems = json_encode($invoiceItems);
        }
        // all invoice items are paid
        else{
            $total_sum = $this->get_invoice_total();
            $transactionAmount = $total_sum['total_sum'];
            $invoiceItems = '';
        }

        $fillable = [
            'user_id'       => $user_id,
            'invoice_id'    => $this->id,
            'invoice_items' => $invoiceItems,
            'transaction_amount'    => $transactionAmount,
            'transaction_currency'  => Config::get('constants.finance.currency'),
            'transaction_type'  => $transaction_type,
            'transaction_date'  => Carbon::now()->format('Y-m-d'),
            'status'        => $status,
            'other_details' => is_array($otherDetails)?json_encode($otherDetails):$otherDetails,
        ];

        $validator = Validator::make($fillable, InvoiceFinancialTransaction::rules('POST'), InvoiceFinancialTransaction::$message, InvoiceFinancialTransaction::$attributeNames);
        if ($validator->fails()){
            return [
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray()];
        }

        $transaction = InvoiceFinancialTransaction::
            where('invoice_id','=',$this->invoice_id)
            ->whereIn('status',['pending','processing'])
            ->orderBy('created_at','DESC')->get()->first();
        if ($transaction){
            // we cancel pending and processing transactions and add the new one
            $transaction->status='cancelled';
            $transaction->save();
        }

        try {
            $newTransaction = InvoiceFinancialTransaction::create($fillable);

            $this->check_if_paid();
            return [
                'success'           => true,
                'transaction_id'    => $newTransaction->id,
                'transaction_status'=> $newTransaction->status];
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'errors'  => 'Error Creating Transaction!'];
        }
    }

    public function check_if_paid(){
        $invoiceTotal = $this->get_invoice_total();

        $transactions_total = 0;
        $transactions = InvoiceFinancialTransaction::where('invoice_id','=',$this->id)->whereIn('status',['completed'])->get();
        foreach($transactions as $one){
            $transactions_total+=$one->transaction_amount;
        }

        if ($invoiceTotal['total_sum'] == $transactions_total){
            $this->status = 'completed';
        }
        else{
            $this->status = 'processing';
        }
        $this->save();
    }

    public function add_credit_invoice($invoiceItem, $employeeId=0){
        if ($employeeId==0){
            $employee = User::where('username','=','sysagent')->get()->first();
            if ($employee){
                $employeeId = $employee->id;
            }
            else{
                $employeeId = 38;
            }
        }

        $invoice = Invoice::where('id','=',$invoiceItem->invoice_id)->get()->first();
        if (sizeof($invoice)==0){
            return ['success' => false, 'errors' => 'Could not find invoice'];
        }

        $other_details = 'Cancellation of the Invoice Item with ID: '.$invoiceItem->id.' and return of item value in store credit';
        $fillable = [
            'user_id'       => $invoice->user_id,
            'employee_id'   => $employeeId,
            'invoice_type'  => 'store_credit',
            'invoice_reference_id'  => $invoice->id,
            'invoice_number'=> $this->next_invoice_number(),
            'other_details' => $other_details,
            'status'        => 'pending'
        ];

        $validator = Validator::make($fillable, Invoice::rules('POST'), Invoice::$message, Invoice::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        $invoice = Invoice::create($fillable);

        $storeCreditItem = [
            'item_name' => 'Refund for : '.$invoiceItem->item_name,
            'item_type' => 'store_credit_item',
            'item_reference_id' => $invoiceItem->id,
            'quantity'  => $invoiceItem->quantity,
            'price'     => -$invoiceItem->price,
            'discount'  => $invoiceItem->discount,
            'vat'       => $invoiceItem->vat
        ];
        $invoice->add_invoice_item($storeCreditItem);

    }
}
