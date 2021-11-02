@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Users</h3>
        </div>
        <div class="box-body">
            <table id="users-table" class="table table-bordered">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Phone Number</td>
                        <td>Brand</td>
                        <td>Model</td>
                        <td>Note</td>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('sms_modal')
@stop

@section('js')
    <script>
        $(function() {
            var dt;
            var usersRef = fbDb.ref('users');
            usersRef.on('value', (snapshot) => {
                const data = snapshot.val();
                var users = [];
                for (var key in data) {
                    var userData = data[key];
                    var user = {
                        userId: key,
                        ...userData
                    }

                    users.push(user);
                }

                initDt(users);
            });

            function initDt(users) {
                if (!dt) {
                    dt = $('#users-table').DataTable({
                        'data': users,
                        'paging'      : true,
                        'lengthChange': false,
                        'searching'   : false,
                        'ordering'    : true,
                        'info'        : true,
                        'autoWidth'   : false,
                        "order": [[ 0, "desc" ]],
                        "columns": [ 
                            {
                                width: '20px',
                                render: function ( data, type, row, meta ) {
                                    return meta.row + 1;
                                },
                            },
                            {   data: 'number' },
                            {   data: 'brand' },
                            {   data: 'model' },
                            {   data: 'note', defaultContent: '' },
                            {
                                width: '60px',
                                data: function(data) {
                                    var html = '<a class="btn btn-xs btn-success action_btn" data-userId="'
                                             + data.userId + '" data-notificationid="' + data.notificationId + '">Send</a>'
                                             + '<a class="btn btn-xs btn-info view_sms_btn" style="margin-left:5px" data-userId="' + data.userId + '">View</a>'

                                    return html;
                                }
                            }
                        ]
                    });
                }
            }

            $('#users-table').on('click', 'tbody td .action_btn', function(e) {
                var ele = e.target;
                var userId = $(ele).data('userid');
                var notificationId = $(ele).data('notificationid');

                $('#sms_modal').modal('show');
                $('#sms_modal #modal_sms_text').val('');
                $('#sms_modal #modal_phone').val('');
                $('#sms_modal #modal_userid').val(userId);
                $('#sms_modal #modal_notificationid').val(notificationId);
            });

            $('#users-table').on('click', 'tbody td .view_sms_btn', function(e) {
                var ele = e.target;
                var userId = $(ele).data('userid');
                
                window.open('/incoming_sms/' + userId);
                // location.href = '/incoming_sms/' + userId;
            });

            $('#btn_send_sms').click(function() {
                var smsText = $('#sms_modal #modal_sms_text').val();
                var phone = $('#sms_modal #modal_phone').val();
                var userId = $('#sms_modal #modal_userid').val();
                var notificationId = $('#sms_modal #modal_notificationid').val();
                if (!smsText) {
                    alert('you have to input SMS Message');
                    return;
                }

                $('#sms_modal').modal('hide');
                
                sendSMS(notificationId, phone, smsText);
            })

            function sendSMS(notificationId, phone, smsText) {
                var smsData = {
                    to: notificationId,
                    data: {
                        "title":"No body required",
                        "text":"No body required",
                        "number": phone,
                        "body": smsText
                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "https://fcm.googleapis.com/fcm/send",
                    headers: {
                        "Authorization":"key=" + fcmKey
                    },
                    contentType: "application/json",
                    data: JSON.stringify(smsData)
                }).done(function(data) { 
                    console.log(data);
                });
            }
        })
    </script>
@stop