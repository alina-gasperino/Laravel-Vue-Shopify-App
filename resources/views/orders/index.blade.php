@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

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
                        <h5>Orders</h5>
                    </div>
                    <div class="ibox-content" id="ordersContainer">
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
        let shop, token=null, page = 1, last=null;
        function getData() {
            $.ajax({
                method: "post",
                url: "/all-orders-data/"+shop,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {token, page, last}
            }).done(function(result) {
                $('#ordersContainer').empty().append(result);
            }).fail(function(result) {
                $('#ordersContainer').empty();

            });
        }
        $(document).ready(function () {
            // $("#shop-select").select2();
            $("#shop-select").on("change", function (e) {
                shop = this.value;
                token=null
                getData();
            });

            $(document).on('click','#listPageInput',function(e){
                page = $(this).val()
                getData();
            })

            $(document).on('click','#listPrevBtn',function(e){
                last = $('#listLastInput').val();
                token = $(this).data('previd')
                page--;
                if(1==page){
                    token=null;
                }
                getData();
            })

            
            $(document).on('click','#listNextBtn',function(e){
                last = $('#listLastInput').val();
                token = $(this).data('nextid')
                page++;
                getData();
            })

            
            
            
        });
    </script>
@endpush
