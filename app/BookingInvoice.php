<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use App\ShopLocations;
use App\ShopResource;
use App\VatRate;
use Carbon\Carbon;
use Auth;

class BookingInvoice extends Model
{
    protected $table = 'booking_invoices';

    public static $attributeNames = array(
        'booking_id'    => 'Booking ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status',
    );

    public static $message = array();

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'other_details',
        'status',
    ];

    public static function next_invoice_number(){
        $booking = BookingInvoice::select('invoice_number')->orderBy('invoice_number', 'desc')->get()->first();
        if ($booking){
            return ($booking->invoice_number+1);
        }
        else{
            return 1100;
        }
    }

    public function add_invoice_item($bookingID, $update_assigned_invoice = false){
        // check if the bookingID is linked to any invoice/invoice_item
        $item = BookingInvoiceItem::where('booking_id','=',$bookingID)->get()->first();
        if ($item && $update_assigned_invoice==true){
            // delete the invoice item
            BookingInvoiceItem::where('booking_id','=',$bookingID)->delete();
        }
        elseif ($item){
            // return error
            return ['success' => false, 'errors' => 'Booking already assigned to an invoice #'.$item->booking_id];
        }

        $theBooking = Booking::where('id','=',$bookingID)->get()->first();

        $loc = ShopLocations::where('id','=',$theBooking->location_id)->get()->first();
        $location_name = $loc->name;

        $resource = ShopResource::where('id','=',$theBooking->resource_id)->get()->first();
        $resource_name = $resource->name;

        $booking_date = $theBooking->date_of_booking;
        $booking_time_interval = $theBooking->booking_time_start.' - '.$theBooking->booking_time_stop;

        $booking_price = $theBooking->payment_amount;

        $vat = VatRate::orderBy('id','asc')->get()->first();
        $vat_value = $vat->value;

        $total_price = $booking_price + (($booking_price*$vat_value)/100);

        $fillable = [
            'booking_invoice_id'=> $this->id,
            'booking_id'        => $theBooking->id,
            'location_name'     => $location_name,
            'resource_name'     => $resource_name,
            'quantity'          => 1,
            'booking_date'      => $booking_date,
            'booking_time_interval' => $booking_time_interval,
            'price'             => $booking_price,
            'vat'               => $vat_value,
            'discount'          => 0,
            'total_price'       => $total_price
        ];

        $validator = Validator::make($fillable, BookingInvoiceItem::rules('POST'), BookingInvoiceItem::$message, BookingInvoiceItem::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_invoice_item = BookingInvoiceItem::create($fillable);

            if ($the_invoice_item){
                return ['success' => true, 'message' => 'Item Added', 'id' => $the_invoice_item->id];
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
        $invoiceItems = BookingInvoiceItem::where('booking_invoice_id','=',$this->id)->get();
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

    public function add_transaction($fillable){
        $fillable['booking_invoice_id'] = $this->id;

        $validator = Validator::make($fillable, BookingFinancialTransaction::rules('POST'), BookingFinancialTransaction::$message, BookingFinancialTransaction::$attributeNames);
        if ($validator->fails()){
            return [
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()];
        }

        $transaction = BookingFinancialTransaction::
            where('booking_invoice_id','=',$this->booking_invoice_id)
            ->whereIn('status',['pending','processing'])
            ->orderBy('created_at','DESC')->get()->first();
        if ($transaction){
            // we cancel pending and processing transactions and add the new one
            $transaction->status='cancelled';
            $transaction->save();
        }

        try {
            $newTransaction = BookingFinancialTransaction::create($fillable);
            return [
                'success'=>true,
                'transaction_id' => $newTransaction->id,
                'transaction_status' => $newTransaction->status];
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'errors' => 'Error Creating Transaction!'];
        }
    }

    public function make_general_invoice(){
        $bookingInvoiceID = $this->id;

        $booking = Booking::where('id','=',$this->booking_id)->get()->first();
        if (!$booking){
            return false;
        }

        // see if someone is logged in
        if (!Auth::check()) {
            $general_invoice_employee = Auth::user();
        }
        else{
            $general_invoice_employee = $booking->by_user_id;
        }

        $generalFillable = [
            'user_id'       => $booking->for_user_id,
            'employee_id'   => $general_invoice_employee,
            'invoice_type'  => 'booking_invoice',
            'invoice_reference_id'  => $bookingInvoiceID,
            'invoice_number'        => Invoice::next_invoice_number()
        ];
        $general_invoice = Invoice::create($generalFillable);

        $bookingInvoiceItems = BookingInvoiceItem::where('booking_invoice_id','=',$bookingInvoiceID)->get();
        foreach ($bookingInvoiceItems as $bookingItem){
            $generalFillable = [
                'item_name'         => $bookingItem->location_name.', room '.$bookingItem->resource_name.' - '.$bookingItem->booking_time_interval.' on '.$bookingItem->booking_date.' ',
                'item_type'         => 'booking_invoice_item',  // type of the item : membership payment, coupon code, discounts, bookings, products
                'item_reference_id' => $bookingItem->id,    // the ID from the table
                'quantity'      => $bookingItem->quantity,  // number of items
                'price'         => $bookingItem->price,     // base price
                'vat'           => $bookingItem->vat,       // VAT for the product group
                'discount'      => $bookingItem->discount,  // applied discount
            ];
            $general_invoice->add_invoice_item($generalFillable);
        }

        return true;
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
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            default:break;
        }
    }

    public function financial_transaction(){
        return $this->hasMany('App\BookingFinancialTransaction', 'booking_invoice_id', 'id')->orderBy('created_at','DESC');
    }

    public function invoice_items(){
        return $this->hasMany('App\BookingInvoiceItem', 'booking_invoice_id', 'id')->orderBy('created_at','DESC');
    }
}
