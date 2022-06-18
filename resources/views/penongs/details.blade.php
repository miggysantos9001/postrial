@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Bookings Page</h1>
        <h3 style="color:blue;">{{ $customer->name }}</h3>
        <h3 style="color:green;">{{ \Carbon\Carbon::parse($booking->date)->toFormattedDateString() }}</h3>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	{!! Form::open(['method'=>'POST','action'=>['PenongsController@post_booking_details',$booking->id,$customer->id]]) !!}
        <div class="row">
    		<div class="col-md-12">
                <a href="{{ action('PenongsController@show',$booking->id) }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to View Bookings</a>
                <table class="table table-bordered" id="">
                    <thead>
                        <tr>
                            <th class="text-center" width="120">Product</th>
                            <th class="text-center" width="120">Heads</th>
                            <th class="text-center" width="120">Weight</th>
                            <th class="text-center" width="120">Unit Price</th>
                            <th class="text-center" width="120">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $data)
                        <?php 
                            //$defPrice = \App\Customer_pricing::where('customer_id',$customer->id)
                                //->where('product_id',$data->product_id)
                               // ->first();
                        ?>
                        <tr>
                            <td>{{ $data->product->name }}</td>
                            <td>
                                {{ Form::text('heads[]',$data->heads * $data->product->bags,['class'=>'form-control heads','style'=>'width:100%;','id'=>'heads']) }}
                                {{ Form::hidden('id[]',$data->id,['class'=>'form-control ','style'=>'width:100%;','id'=>'']) }}
                            </td>
                            <td>
                                @if($data->weight == NULL)
                                <input type="text" data-id="{{ $data->id}}" name="weight[]" value="0.00" class="form-control weight" id="weight_{{ $data->id }}" />
                                @else
                                <input type="text" data-id="{{ $data->id}}" name="weight[]" value="{{ $data->weight }}" class="form-control weight" id="weight_{{ $data->id }}" />
                                @endif
                            </td>
                            <td>
                                
                                @if($data->unit_price == NULL)
                                <input type="text"  data-id="{{ $data->id}}" name="unit_price[]" value="0.00" class="form-control unit_price" id="unit_price_{{ $data->id }}" />
                                @else
                                <input type="text"  data-id="{{ $data->id}}" name="unit_price[]" value="{{ $data->unit_price }}" class="form-control unit_price" id="unit_price_{{ $data->id }}" />
                                @endif
                                
                            </td>
                            </td>
                            <td>
                                @if($data->total_price == NULL)
                                <input type="text" name="total_price[]" class="form-control amount" id="amount_{{ $data->id }}" value="0.00" />
                                @else
                                <input type="text" name="total_price[]" class="form-control amount" id="amount_{{ $data->id }}" value="{{ $data->total_price }}" readonly="readonly" />
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>      
            </div>
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
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
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

        $('.weight,.unit_price').change(function(){
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