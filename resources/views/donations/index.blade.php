@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Donation Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::open(['method'=>'POST','action'=>'DonationController@create_booking_form']) !!}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('','Select Date') !!}
                    {!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    {!! Form::label('','Select Client') !!}
                    {!! Form::select('client_id',$clients,null,['class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'PLEASE SELECT']) !!}
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Donation</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Donation List</h3>
                    </div>
                    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Client</th>
                                    <th class="text-center" width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donations as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                                    <td>{{ $data->client->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ action('DonationController@edit',$data->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('DonationController@delete',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
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
