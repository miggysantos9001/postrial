@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Expense Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-4">
    			{!! Form::open(['method'=>'POST','action'=>'ExpenseController@store']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Expense</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-12">
		            			<div class="form-group">
				                	{!! Form::label('Date') !!}
				                	{!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
				                </div>
		            		</div>	
		            	</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Select Expense Type') !!}
                                    {!! Form::select('expensetype_id',$expensetypes,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Enter Amount') !!}
                                    {!! Form::text('amount','0.00',['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Remarks') !!}
                                    {!! Form::textarea('remarks',null,['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
		            </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    		<div class="col-md-8">
    			<div class="box">
    			    <div class="box-header with-border">
    			        <h3 class="box-title">Expense List</h3>
    			    </div>
    			    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Expense Type</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center" width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                                    <td>{{ $data->expensetype->name }}</td>
                                    <td>{{ number_format($data->amount,2) }}</td>
                                    <td class="text-center">
                                        <a href="#edit{{ $data->id }}" class="btn btn-success btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('ExpenseController@delete',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="edit{{ $data->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Edit {{ $data->name }}</h4>
                                            </div>
                                            {!! Form::open(['method'=>'PATCH','action'=>['ExpenseController@update',$data->id]]) !!}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Date') !!}
                                                            {!! Form::text('date',$data->date,['class'=>'form-control']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Select Expense Type') !!}
                                                            {!! Form::select('expensetype_id',$expensetypes,$data->expensetype_id,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Enter Amount') !!}
                                                            {!! Form::text('amount',$data->amount,['class'=>'form-control']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Remarks') !!}
                                                            {!! Form::textarea('remarks',$data->remarks,['class'=>'form-control']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
    			    </div>
    			</div>
    		</div>
    	</div>
    	
    </section>
</div>
@endsection