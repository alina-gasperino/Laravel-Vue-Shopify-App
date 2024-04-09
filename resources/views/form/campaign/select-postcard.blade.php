<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Step 3: Design Your Postcard
    </div>
    <div class="ibox-content">
        <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
        </div>

        <input type="hidden" name="project_id" id="input_project_id" value="">
        <input type="hidden" name="thumbnail_url" id="input_thumbnail_url" value="">
        <div id="designPostcardContent">
            <p class="custom-text">Choose from hundreds of pre-made, conversion optimized templates to amplify your sales</p>
            <div style="padding: 20px 10px 0px 0px">
                <button type="button" id="openPostCard" class="btn custom-button btn" data-toggle="modal"
                        data-target="#postCardsTemplates">Open Designer
                </button>
            </div>
            <div class="modal inmodal fade" id="postCardsTemplates" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-90">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Pick Your Template</h4>
                        </div>
                        <div class="modal-body" style="min-height: 700px;padding: 0px 0px">
                            <iframe frameborder="0" src=
                            "https://simplepost.designhuddle.com/projects?noheader=1&token={{ $userToken }}#/create"
                                    style="overflow: hidden; height: 100%; width:100%; position: absolute"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal inmodal fade" id="designCard" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-90">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Personalize Your Postcard</h4>
                    </div>
                    <div style="height: 30px; margin: 18px 25px">
                        <button onclick="saveDesign()" id="savePostCard" type="button"
                                class="btn btn-lg btn-primary pull-right">Save & Continue
                        </button>
                    </div>
                    <div id="designCardBody" class="modal-body" style="min-height: 700px;padding: 0px 0px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function bindEvent(element, eventName, eventHandler) {
            if (element.addEventListener) {
                element.addEventListener(eventName, eventHandler, false);
            } else if (element.attachEvent) {
                element.attachEvent('on' + eventName, eventHandler);
            }
        }

        bindEvent(window, 'message', function (e) {
            if (e && e.data && typeof e.data === 'string') {
                try {
                    var data = JSON.parse(e.data);
                    if (data && data.type === 'DSHD_GOTO_PROJECT') {
                        let projectId = data.payload.project_id
                        let iFrame = '<iframe frameborder="0" ' +
                            'src="https://simplepost.designhuddle.com/editor?token={{ $userToken }}&project_id=' +
                            data.payload.project_id +
                            '" style = "overflow: hidden; height: 90%; width:90%; position: absolute"></iframe>';
                        $('#input_project_id').val(projectId)
                        $('#designCardBody').html(iFrame);
                        $('#postCardsTemplates').modal('hide');
                        window.setTimeout(showDesign, 500);

                    }
                } catch {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                }
            }
        })

        function showDesign() {
            $('#designCard').modal('show');
        }

        function saveDesign() {
            $('#designCard').modal('hide');
            let $projectId = '';
            projectId = $('#input_project_id').val();
            $.get("{{ route('getCampaignThumbnail') }}", {project_id: projectId}, function (data) {
                $('#input_thumbnail_url').val(data.thumbnail_url)
                $("#designPostcardContent").html('<img src="' + data.thumbnail_url + '" />')
            });
        }
    </script>
@endpush
