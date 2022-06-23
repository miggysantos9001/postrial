<?php

namespace App\Http\Controllers;

use App\Order;
use App\Booking;
use App\Booking_detail;
use App\Customer;
use App\Client;
use App\Product;
use Validator;
use Auth;
use PDF;
use DB;
use Session;
use Input;
use Request;
use DateTime;
use Hash;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function view_expenses(){

        return view('reports.expenses');
    }

    public function post_expenses(){
        $validator = Validator::make(Request::all(), [
            'month_id'               =>  'required',
            'year_id'                =>  'required',
        ],
        [
            'month_id.required'      =>  'Please select month',
            'year_id.required'       =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $expenses = \App\Expense::with('expensetype')->whereMonth('date','=',$month)
                ->whereYear('date','=',$year)
                ->orderBy('date','DESC')
                ->get();
        
        return view('pdf.expensesPDF',compact('monthName','year','expenses'));
    }

    public function view_bank_balance(){

        return view('reports.bank');
    }

    public function post_bank_balance(){
        $validator = Validator::make(Request::all(), [
            'month_id'               =>  'required',
            'year_id'                =>  'required',
        ],
        [
            'month_id.required'      =>  'Please select month',
            'year_id.required'       =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');
        $bank = \App\Start_balance::first();

        /*TEST*/

        $prevSales = \App\Booking_detail::whereMonth('date','<',$month)
                ->whereYear('date','=',$year)
                ->sum('total_price');

        $prevSVI = \App\Order_detail::whereMonth('date','<',$month)
                ->whereYear('date','=',$year)
                //->whereYear('date','=',Carbon::now()->year)
                ->sum('total_price');

        $prevExpense = \App\Expense::whereMonth('date','<',$month)
                ->whereYear('date','=',$year)
                //->whereYear('date','=',Carbon::now()->year)
                ->sum('amount');

        $bal = $bank->balance + ($prevSales - $prevSVI + $prevExpense);

        //dd($bank->balance + $prevSales);
        //dd($bal);

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $str_month = $monthName;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $data = array();

        $sviTotal = $salesTotal = $expenseTotal = 0;

        for($i = 1; $i<= $days; $i++){
            $str_date = "$str_month $i, $year";
            $dt = date('F j, Y', strtotime($str_date));

            $sviFull = \App\Order_detail::where('date',Carbon::parse($dt)->toDateString())->sum('total_price');
            $salesFull = \App\Booking_detail::where('date',Carbon::parse($dt)->toDateString())->sum('total_price');
            $expenseFull = \App\Expense::where('date',Carbon::parse($dt)->toDateString())->sum('amount');

            $data[$i]['dt'] = $dt;
            $data[$i]['sviFull'] = $sviFull;
            $data[$i]['salesFull'] = $salesFull;
            $data[$i]['expenseFull'] = $expenseFull;
            $data[$i]['bank'] = $sviFull;

            $sviTotal += $sviFull;
            $salesTotal += $salesFull;
            $expenseTotal += $expenseFull;
        }

        return View('pdf.bankPDF', compact('data','monthName','year','sviTotal','salesTotal','expenseTotal','bank','bal'));
    }

    public function view_lost_kilo_report(){
        return view('reports.lost-kilo-report');
    }

    public function post_lost_kilo_report(){
        $validator = Validator::make(Request::all(), [
            'month_id'               =>  'required',
            'year_id'                =>  'required',
        ],
        [
            'month_id.required'      =>  'Please select month',
            'year_id.required'       =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $losts = \App\Lost_kilo::whereMonth('date','=',$month)
            ->whereYear('date','=',$year)
            ->get();

        return View('pdf.lostkiloPDF', compact('losts','monthName','year'));
    }

    public function view_lost_kilos(){
        $customer = NULL;
        $customers = \App\Customer::orderBy('name')->get()->pluck('name','id');
        return view('reports.lost-kilos',compact('customers','customer'));
    }

    public function post_lost_kilos(){
        $validator = Validator::make(Request::all(), [
            'customer_id'               =>  'required',
            'date'                      =>  'required',
        ],
        [
            'customer_id.required'      =>  'Please select customer',
            'date.required'             =>  'Please select date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customer = Request::get('customer_id');
        $date = Request::get('date');

        $list = \App\Booking_detail::where('date',$date)
            ->where('customer_id',$customer)
            ->get();

        if($list->count() == 0){
            notify()->error('Booking Date Not Found!');
            return redirect()->back();
        }

        $custName = Customer::find($customer);

        return View('reports.lost-bookings', compact('list','custName','date'));
    }

    public function post_lost_kilos_values($id){
        $booking = \App\Booking_detail::find($id);
        $lostkilo = Request::get('lostkilo');

        \App\Lost_kilo::create([
            'booking_detail_id'     =>      $booking->id,
            'date'                  =>      $booking->date,
            'customer_id'           =>      $booking->customer_id,
            'lost'                  =>      $booking->weight - $lostkilo,
        ]);

        $booking->update([
            'weight'        =>      $lostkilo,
            'total_price'   =>      $lostkilo * $booking->unit_price,
        ]);

        notify()->success('Booking Detail Updated');
        return redirect()->back();
    }

    public function view_unpaids(){
        $customers = \App\Customer::orderBy('name')->get()->pluck('name','id');
        return view('reports.unpaids',compact('customers'));
    }

    public function post_unpaids(){
        $customer_id = Request::get('customer_id');
        $month_id = Request::get('month_id');
        $year_id = Request::get('year_id');
        $all = Request::get('all');

        //dd($customer_id);

        $customer = Customer::find($customer_id);
        $monthNum  = $month_id;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $test = [];

        $un = \App\Booking::whereMonth('date',$month_id)
            ->whereYear('date',$year_id)
            ->orderBy('date')
            ->get();

        foreach($un as $u){
            $test[] = $u->id;
        }

        if(Request::get('all') == 1){
            $unpaids = \App\Booking_list::with('booking')->whereIn('booking_id',$test)
                //->where('customer_id',Request::get('customer_id'))
                ->where('payment_id',NULL)
                ->get()
                ->sortBy('booking.date');

            $withBalance = \App\Booking_list::with('booking')->whereIn('booking_id',$test)
                //->where('customer_id',Request::get('customer_id'))
                ->where('payment_id','!=',NULL)
                ->where('isPaid',0)
                ->get()
                ->sortBy('booking.date');

        }else{
            $unpaids = \App\Booking_list::with('booking')->whereIn('booking_id',$test)
                ->where('customer_id',Request::get('customer_id'))
                ->where('payment_id',NULL)
                ->get()
                ->sortBy('booking.date');

            $withBalance = \App\Booking_list::with('booking')->whereIn('booking_id',$test)
                ->where('customer_id',Request::get('customer_id'))
                ->where('payment_id','!=',NULL)
                ->where('isPaid',0)
                ->get()
                ->sortBy('booking.date');
        }

        
        
        return View('pdf.unpaidsPDF', compact('unpaids','withBalance','monthName','year_id','customer','all'));
    }

    public function view_discrepancy(){
        $clients = Client::orderBy('name')->get()->pluck('name','id');
        return view('reports.discrepancy',compact('clients'));
    }

    public function post_discrepancy(){
        $validator = Validator::make(Request::all(), [
            'client_id'         =>  'required',
            'date_from'         =>  'required|date',
            'date_to'           =>  'required|date|after_or_equal:date_from',
        ],
        [
            'client_id.required'        =>  'Please select client',
            'date_from.required'        =>  'Please select date from',
            'date_to.required'          =>  'Please select date to',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date_from = Request::get('date_from');
        $date_to = Request::get('date_to');
        $client = Request::get('client_id');

        $theclient = Client::find($client);

        $array = array();

        $interval = new \DateInterval('P1D');

        $realEnd = new \DateTime(Request::get('date_to'));
        $realEnd->add($interval);

        $period = new \DatePeriod(new \DateTime(Request::get('date_from')), $interval, $realEnd);
        $format = 'Y-m-d';
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        return View('pdf.discrepancyPDF', compact('array','date_from','date_to','client','theclient'));
    }

    public function view_report_soa(){
        $customers = \App\Customer::orderBy('name')->get()->pluck('name','id');
    	return view('reports.summary-soa',compact('customers'));
    }

    public function post_report_soa(){
        $validator = Validator::make(Request::all(), [
            'customer_id'         =>  'required',
            'month_id'          =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'customer_id.required'        =>  'Please select client',
            'month_id.required'         =>  'Please select month',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
    	$year = Request::get('year_id');
        $customer = Customer::where('id',Request::get('customer_id'))->first();

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $str_month = $monthName;
    	$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $data = array();

        $data = Booking_detail::where('customer_id',$customer->id)
            ->whereMonth('date',$month)
            ->whereYear('date',$year)
            ->where('weight','>',0)
            ->orderBy('date')
            ->get();

        // $pheads = Product::where('isHead',1)->get();

        // for($i = 1; $i<= $days; $i++){
        // 	$str_date = "$str_month $i, $year";
        // 	$dt = date('F j, Y', strtotime($str_date));
            
        //     $data[$i]['dt'] = $dt;
        // }

        return View('pdf.newsummaryPDF',compact('monthName','year','data','customer'));

    }
    
    public function view_summary(){
        $clients = \App\Client::orderBy('name')->get()->pluck('name','id');
    	return view('reports.summary',compact('clients'));
    }

    public function post_summary(){
        $validator = Validator::make(Request::all(), [
            'client_id'         =>  'required',
            'month_id'          =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'client_id.required'        =>  'Please select client',
            'month_id.required'         =>  'Please select month',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


    	$month = Request::get('month_id');
    	$year = Request::get('year_id');
        $client = Request::get('client_id');


        $cgArray = array();    

        $theclient = \App\Client::find($client);

        /*foreach($theclient->members as $tcm){
            $cgArray[] = $tcm->client_id;
        }*/

    	$monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March

        $str_month = $monthName;
    	$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $data = array();

        $sviTotal = $salesTotal = $profitTotal = $overall = $cbtotal = 0;

        for($i = 1; $i<= $days; $i++){
        	$str_date = "$str_month $i, $year";
        	$dt = date('F j, Y', strtotime($str_date));

        	$sviFull = \App\Order_detail::where('client_id',$client)->where('date',Carbon::parse($dt)->toDateString())->sum('total_price');
        	$salesFull = \App\Booking_detail::where('client_id',$client)->where('date',Carbon::parse($dt)->toDateString())->sum('total_price');
            $booking = \App\Booking::where('client_id',$client)->where('date',Carbon::parse($dt)->toDateString())->first();
            $cb = \App\Order::where('client_id',$client)->where('date',Carbon::parse($dt)->toDateString())->sum('cashbond');


            if($booking != NULL){
                $tax = \App\Booking_list::where('booking_id',$booking->id)->sum('tax');
            }else{
                $tax = 0;
            }

        	$data[$i]['dt'] = $dt;
        	$data[$i]['sviFull'] = $sviFull;
        	$data[$i]['salesFull'] = $salesFull - $tax;
            $data[$i]['profit'] = $salesFull - $sviFull;
            $data[$i]['cb'] = $cb;
            $data[$i]['overall'] = ($salesFull - $sviFull) + $cb;

        	$sviTotal += $sviFull;
            $salesTotal += $salesFull;
            $profitTotal += $salesFull - $sviFull;
            $cbtotal += $cb; 
            $overall += $profitTotal + $cb;
        }

        //dump($data);

        return View('pdf.summaryPDF', compact('data','monthName','year','sviTotal','salesTotal','profitTotal','theclient','overall','cbtotal'));
    }

    public function view_sales_summary(){
    	return view('reports.sales-summary');
    }

    public function post_sales_summary(){
        $validator = Validator::make(Request::all(), [
            'month_id'               =>  'required',
            'year_id'                =>  'required',
        ],
        [
            'month_id.required'      =>  'Please select month',
            'year_id.required'       =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
		$customers = Customer::orderBy('name')->get();

		$month = Request::get('month_id');
		$year = Request::get('year_id');

		$monthNum  = $month;
	    $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
	    $monthName = $dateObj->format('F'); // March

	    $str_month = $monthName;
		$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
	    $data = array();

	    $sviTotal = $salesTotal = $profitTotal = 0;

	    for($i = 1; $i<= $days; $i++){
	    	$str_date = "$str_month $i, $year";
	    	$dt = date('F j, Y', strtotime($str_date));

	    	$data[$i]['dt'] = Carbon::parse($dt)->toDateString();
	    }

	    return View('pdf.salesPDF', compact('data','monthName','year','customers','month'));
    }

    public function view_daily_sales_summary(){
    	return view('reports.daily-sales-summary');
    }

    public function post_daily_sales_summary(){
    	$array = array();
    	$dateFrom = Request::get('date_from');
    	$dateTo = Request::get('date_to');

        $interval = new \DateInterval('P1D');

        $realEnd = new \DateTime(Request::get('date_to'));
        $realEnd->add($interval);

        $period = new \DatePeriod(new \DateTime(Request::get('date_from')), $interval, $realEnd);
        $format = 'Y-m-d';
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        $products = \App\Product::orderBy('position')->get();
        $customers = \App\Customer::orderBy('name')->get();
    	return View('pdf.dailysalesPDF', compact('array','dateFrom','dateTo','products','customers'));
    }

    public function view_customer_soa(){
        $customers = \App\Customer::orderBy('name')->get()->pluck('name','id');
        return view('reports.customer-soa',compact('customers'));
    }

    public function post_customer_soa(){
        $customer = \App\Customer::where('id',Request::get('customer_id'))->first();

        $isPaid = Request::get('isPaid');
        $customer_id = Request::get('customer_id');

        if($isPaid == 0){
            $status = 'UNPAID';
        }else{
            $status = 'PAID';
        }

        $list = \App\Booking_list::with('booking')->where('customer_id',$customer_id)->where('isPaid',$isPaid)->get()->sortBy('booking.date');

        $array = array();

        $totalamount = $totalpayment = $totalbalance = 0;
        foreach($list as $data){
            $array[] = array(
                'date'              =>      Carbon::parse($data->booking->date)->toFormattedDateString(),
                'amount'            =>      number_format($data->total_price,2),
                'payment'           =>      number_format($data->paymentMade,2),
                'balance'           =>      number_format($data->total_price - $data->paymentMade,2),
                'paymentdate'       =>      Carbon::parse($data->datePaid)->toFormattedDateString(),
                'days'              =>      Carbon::parse($data->datePaid)->diffInDays(Carbon::parse($data->booking->date)),
            );

            $totalamount += $data->total_price;
            $totalpayment += $data->paymentMade;
            $totalbalance += $data->total_price - $data->paymentMade;
        }

        //dump($array);
        return View('pdf.soaPDF', compact('array','customer','totalamount','totalpayment','totalbalance','status'));
    }

    public function view_order_report(){
        $clients = \App\Client::orderBy('name')->get()->pluck('name','id');
        return view('reports.orders',compact('clients'));
    }
    

    public function post_order_report(){
        $validator = Validator::make(Request::all(), [
            'client_id'         =>  'required',
            'month_id'          =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'client_id.required'        =>  'Please select client',
            'month_id.required'         =>  'Please select month',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');
        $client = Request::get('client_id');

        $products = \App\Product::orderBy('position')->get();
        $clientname = \App\Client::find($client);

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');

        return View('pdf.ordersPDF', compact('products','month','year','client','clientname','monthName'));
    }

    public function view_donation_report(){
        $clients = \App\Client::orderBy('name')->get()->pluck('name','id');
        return view('reports.donations',compact('clients'));
    }

    public function post_donation_report(){
        $validator = Validator::make(Request::all(), [
            'client_id'         =>  'required',
            'month_id'          =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'client_id.required'        =>  'Please select client',
            'month_id.required'         =>  'Please select month',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');
        $client = Request::get('client_id');

        $products = \App\Product::orderBy('position')->get();
        $clientname = \App\Client::find($client);

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');

        return View('pdf.donationsPDF', compact('products','month','year','client','clientname','monthName'));
    }

    public function view_customer_order_report(){
        $customers = \App\Customer::orderBy('name')->get()->pluck('name','id');
        return view('reports.customer-orders',compact('customers'));
    }

    public function post_customer_order_report(){
        $validator = Validator::make(Request::all(), [
            'customer_id'       =>  'required',
            'month_id'          =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'customer_id.required'      =>  'Please select customer',
            'month_id.required'         =>  'Please select month',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');
        $customer = Request::get('customer_id');

        $products = \App\Product::orderBy('position')->get();
        $customername = \App\Customer::find($customer);

        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');

        return View('pdf.customerorderPDF', compact('products','month','year','customer','customername','monthName'));

    }

    public function yearly_report_per_client(){
        $clients = \App\Client::orderBy('name')->get()->pluck('name','id');
        return view('reports.per-client',compact('clients'));
    }

    public function print_yearly_report_per_client(){
        $validator = Validator::make(Request::all(), [
            'client_id'         =>  'required',
            'year_id'           =>  'required',
        ],
        [
            'client_id.required'        =>  'Please select client',
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = Request::get('year_id');
        $client = Request::get('client_id');
        $theclient = \App\Client::find($client);

        for($m = 1; $m <= 12; ++$m){
            $dt = date('F', mktime(0, 0, 0, $m, 1));

            $sviFull = \App\Order_detail::where('client_id',$client)->whereMonth('date',$m)->whereYear('date',$year)->sum('total_price');
            $salesFull = \App\Booking_detail::where('client_id',$client)->whereMonth('date',$m)->whereYear('date',$year)->sum('total_price');
            $cb = \App\Order::where('client_id',$client)->whereMonth('date',$m)->whereYear('date',$year)->sum('cashbond');
            
            $data[$m]['dt'] = $dt;
            $data[$m]['sviFull'] = $sviFull;
            $data[$m]['salesFull'] = $salesFull;
            $data[$m]['profit'] = $salesFull - $sviFull;
            $data[$m]['cb'] = $cb;

        }

        return View('pdf.yearlyclientPDF', compact('data','theclient','year'));
    }

    public function yearly_report_all_client(){
        return view('reports.all-client',compact('clients'));
    }

    public function print_yearly_report_all_client(){
        $validator = Validator::make(Request::all(), [
            'year_id'           =>  'required',
        ],
        [
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = Request::get('year_id');

        return View('pdf.yearlyallclientPDF', compact('year'));
    }

    public function yearly_sales_report(){
        return view('reports.yearly-report');
    }

    public function print_yearly_sales_report(){
        $validator = Validator::make(Request::all(), [
            'year_id'           =>  'required',
        ],
        [
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = Request::get('year_id');

        for($m = 1; $m <= 12; ++$m){
            $dt = date('F', mktime(0, 0, 0, $m, 1));

            $sviFull = \App\Order_detail::whereMonth('date',$m)->whereYear('date',$year)->sum('total_price');
            $salesFull = \App\Booking_detail::whereMonth('date',$m)->whereYear('date',$year)->sum('total_price');
            $exp = \App\Expense::whereMonth('date',$m)->whereYear('date',$year)->sum('amount');
            $cb = \App\Order::whereMonth('date',$m)->whereYear('date',$year)->sum('cashbond');
            
            $data[$m]['dt'] = $dt;
            $data[$m]['sviFull'] = $sviFull;
            $data[$m]['salesFull'] = $salesFull;
            $data[$m]['profit'] = $salesFull - $sviFull;
            $data[$m]['exp'] = $exp;
            $data[$m]['cb'] = $cb;

        }

        return View('pdf.yearlysalesPDF', compact('data','year'));
    }

    public function monthly_expense_report(){
        return view('reports.monthly-expense');
    }

    public function print_monthly_expense_report(){
        $validator = Validator::make(Request::all(), [
            'year_id'           =>  'required',
            'month_id'          =>  'required',
        ],
        [
            'year_id.required'          =>  'Please select year',
            'month_id.required'         =>  'Please select month',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $month = Request::get('month_id');
        $year = Request::get('year_id');
        $monthNum  = $month;
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');

        $expensetypes = \App\Expensetype::orderBy('name')->get();

        return View('pdf.monthlyexpensePDF', compact('monthName','year','expensetypes','month'));
    }

    public function yearly_expense_report(){
        return view('reports.yearly-expense');
    }

    public function print_yearly_expense_report(){
        $validator = Validator::make(Request::all(), [
            'year_id'           =>  'required',
        ],
        [
            'year_id.required'          =>  'Please select year',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = Request::get('year_id');
        $expensetypes = \App\Expensetype::orderBy('name')->get();

        $list = [];
        foreach($expensetypes as $data){
            $exp_amt = \App\Expense::where('expensetype_id',$data->id)->whereYear('date',$year)->sum('amount');

            $list[] = array(
                'expense_type'      =>      $data->name,
                'amount'            =>      $exp_amt,
            );

        }

        return View('pdf.yearlyexpensePDF', compact('list','year'));

    }
}
