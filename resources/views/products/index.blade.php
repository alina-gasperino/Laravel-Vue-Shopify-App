@extends('layouts.app', ['activePage' => 'products', 'titlePage' => __('Products')])

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-xl-2">
                <div class="form-group mb-3">
                    <select class="form-control custom-input" id="shop-select">
                        <option value="all">Select Shop</option>
                        @foreach($shops as $shop)
                            <option value="{{$shop->id}}">{{$shop->shop_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Products</h5>
                    </div>
                    <div class="ibox-content" id="productsContainer">
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
                            
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')

@endpush
@push('onload-js')
<!-- Include Select2 Library -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush
@push('js')
    <script>
        let shop, token=null;
        function getData() {
            $.ajax({
                method: "post",
                url: "/all-products-data/"+shop,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {token}
            }).done(function(result) {
                $('#productsContainer').empty().append(result);
            }).fail(function(result) {
                $('#productsContainer').empty();

            });
        }
        $(document).ready(function () {
            // $("#shop-select").select2();
            $("#shop-select").on("change", function (e) {
                shop = this.value;
                token=null
                getData();
            });

            $(document).on('click','#listPrevBtn',function(e){
                token = $(this).data('previd')
                getData();
            })

            $(document).on('click','#listNextBtn',function(e){
                token = $(this).data('nextid')
                getData();
            })

            
            
            
        });
    </script>
@endpush
