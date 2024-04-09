@if(!empty($campaigns) &&  count($campaigns) > 0)
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>{{$table_title}}</h5>
            </div>
            <div class="ibox-content">
            <table class="table table-centered table-nowrap table-hover mb-0">
                <thead>
                <tr>
                    <th>Postcard Image</th>
                    <th>Campaign Name</th>
                    <th>Shop Name</th>
                    <th>Automated Or Manual</th>
                    <th>Audience Type</th>
                    <th>Analytic</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                    <!-- Please Remove these in production Starts -->
                <tr>
                        <td><img src="https://simplepost.s3.us-east-2.amazonaws.com/campaigns/thumbnails/c6k4fcnnkde0028rmnyg.jpg"></td>
                        <td>all add to carts</td>
                        <td>simplepostco.myshopify.com</td>
                        <td>Automated</td>
                        <td>Add to Carts</td>
                        <th><a href="#">view</a></th>
                        <td>Active</td>
                        <th><a href="#">Stop</a></th>
                    </tr>
                <tr>
                        <td><img src="https://simplepost.s3.us-east-2.amazonaws.com/campaigns/thumbnails/c6k4fcnnkde0028rmnyg.jpg"></td>
                        <td>rewarding loyalty</td>
                        <td>simplepostco.myshopify.com</td>
                        <td>Manual Retargeting</td>
                        <td>Add to Carts</td>
                        <th><a href="#">view</a></th>
                        <td>Active</td>
                        <th><a href="#">Stop</a></th>
                    </tr>
                <tr>
                        <td><img src="https://simplepost.s3.us-east-2.amazonaws.com/campaigns/thumbnails/c6k4fcnnkde0028rmnyg.jpg"></td>
                        <td>activating customers who haven't purchased in a while</td>
                        <td>simplepostco.myshopify.com</td>
                        <td>Manual Retargeting</td>
                        <td>Add to Carts</td>
                        <th><a href="#">view</a></th>
                        <td>Active</td>
                        <th><a href="#">Stop</a></th>
                    </tr>

                    <!-- Please Remove these in production Ends -->
                @foreach ($campaigns as $campaign)
                    <tr>
                        <td><img src="{{ $campaign->thumbnail_url }}"></td>
                        <td>{{ $campaign->campaign_name }}</td>
                        <td>{{ $campaign->shop_name }}</td>
                        <td>{{ $campaign->campaign_type }}</td>
                        <td>{{ $campaign->audience_size }}</td>
                        <th><a href="{{ Route('analyticsDashboard',$campaign->id)}}">view</a></th>
                        <td>{{ $campaign->state_name }}</td>
                        @if ($campaign->deleted_at === null)
                            <th><a href="{{ Route('campaign-overview.stopCampaign', $campaign->project_id)}}">Stop</a></th>
                        @else
                            <th><a href="{{ Route('campaign-overview.restartCampaign', $campaign->project_id)}}">Restart</a></th>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endif


