<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Currency</th>
            <th>Sub Price</th>
            <th>Total Price</th>
            <th>Total Tax</th>
            <th>Discount Codes</th>
            
        </tr>
    </thead>
    <tbody>
        @php($lastId = null)
        @foreach ($list as $oindex => $order)
            @if($oindex == 0) @php($lastId = $order->id) @endif 
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->currency }}</td>
                <td>{{ $order->current_subtotal_price }}</td>
                <td>{{ $order->current_total_price }}</td>
                <td>{{ $order->current_total_tax }}</td>
                <td>{{ json_encode($order->discount_codes) }}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>
<div class="col-lg-12">
    <input type="hidden" id="listPageInput" value="{{$page}}" />
    <input type="hidden" id="listLastInput" value="{{$lastId}}" />
    <button type="button" class="btn btn-default btn-lg" id="listPrevBtn" data-previd="{{$prevId}}" {{ $prevId =='' ? 'disabled' : ''}}>Previous</button>
    <button type="button" class="btn btn-default btn-lg" id="listNextBtn" data-nextid="{{$nextId}}" {{ $nextId =='' ? 'disabled' : ''}}>Next</button>
</div>
