@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Bank Balance Page</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @include('alert')
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {!! Form::open(['method'=>'POST','action'=>'BankBalanceController@store']) !!}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Starting Balance</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Enter Bank Balance') !!}
                                    {!! Form::text('balance',$start->balance,['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                        <a href="{{ action('CustomerController@index') }}" class="btn btn-success"><i class="fa fa-home"></i> Back to Index</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
</div>
@endsection