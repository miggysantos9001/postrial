@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Order From Supplier</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::open(['method'=>'POST','action'=>'SVIBookingController@store']) !!}
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('SVIBookingController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Order Menu</a>
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Order</h3>
		            </div>
		            <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('','Select Client') !!}
                                    {!! Form::select('client_id',$clients,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                </div>
                            </div>
                        </div>
		            	<div class="row">
		            		<div class="col-md-3">
		            			<div class="form-group">
				                	{!! Form::label('Enter Date') !!}
				                	{!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
				                </div>
		            		</div>	   
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('','Select Term') !!}
                                    {!! Form::select('term_id',$terms,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('Enter Check Number') !!}
                                    {!! Form::text('check_number',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('Enter Due Date') !!}
                                    {!! Form::text('due_date',null,['class'=>'form-control dp']) !!}
                                </div>
                            </div>
		            	</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('','Product') !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('','Heads') !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('','Weight') !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('','Unit Price') !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('','Total Price') !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="clearfix"></div>
                        <div id="wrap">
                            <div class="wrapp">
                                @foreach($products as $data)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="product_name[]" class="form-control" id="" value="{{ $data->name }}" readonly="readonly" />
                                        <input type="hidden" name="product_id[]" class="form-control" id="" value="{{ $data->id }}" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" name="heads[]" class="form-control heads" id="heads" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" data-id="{{ $data->id}}" name="weight[]" value="0.00" class="form-control weight" id="weight_{{ $data->id }}" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text"  data-id="{{ $data->id}}" name="unit_price[]" value="0.00" class="form-control unit_price" id="unit_price_{{ $data->id }}" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" name="total_price[]" class="form-control amount" id="amount_{{ $data->id }}" value="0.00" readonly="readonly" />
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="clearfix"></div>
		            </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('','Total Weight:') !!}
                                    {!! Form::text('',null,['class'=>'form-control','readonly'=>'readonly','id'=>'totweight']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('','Total Amount:') !!}
                                    {!! Form::text('',null,['class'=>'form-control','readonly'=>'readonly','id'=>'total']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('','Cash Bond:') !!}
                                    {!! Form::text('cashbond','0.00',['class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                    </div>
		        </div>
    		</div>
    	</div>
    	{!! Form::close() !!}
    </section>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        calculate();

        $('.weight,.unit_price').keyup(function(){
            var pid = $(this).data('id');
            var weight =$(`#weight_${pid}`).val();
            var unit_price = $(`#unit_price_${pid}`).val();
            var amount = parseFloat(weight) * parseFloat(unit_price);

            $(`#amount_${pid}`).val(amount.toFixed(2)); 
            calculate();
        });
    });

    function calculate() {
        var sum = 0;
        var price = 0;

        $(".weight").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                sum += parseFloat(this.value);
                $(this).css("background-color", "#FEFFB0");
            }
            else if (this.value.length != 0){
                $(this).css("background-color", "red");
            }
        });

        $(".amount").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                price += parseFloat(this.value);
                $(this).css("background-color", "#FEFFB0");
            }
            else if (this.value.length != 0){
                $(this).css("background-color", "red");
            }
        });

        $("#totweight").val(sum.toFixed(2));
        $("#total").val(price.toFixed(2));
    }

</script>
@endsection