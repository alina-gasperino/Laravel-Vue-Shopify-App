<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Vendor</th>
            <th>Product Type</th>
            <th>Status</th>
            <th>Handle</th>
            <th>Publish At</th>
            <th>Created At</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->vendor }}</td>
                <td>{{ $product->product_type }}</td>
                <td>{{ $product->status }}</td>
                <td>{{ $product->handle }}</td>
                <td>{{ $product->published_at }}</td>
                <td>{{ $product->created_at }}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>
<div class="col-lg-12">
    <button type="button" class="btn btn-default btn-lg" id="listPrevBtn" data-previd="{{$prevId}}" {{ $prevId =='' ? 'disabled' : ''}}>Previous</button>
    <button type="button" class="btn btn-default btn-lg" id="listNextBtn" data-nextid="{{$nextId}}" {{ $nextId =='' ? 'disabled' : ''}}>Next</button>
</div>
