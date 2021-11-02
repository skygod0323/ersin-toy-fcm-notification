@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Incoming SMS</h1>
@stop

@section('css')
    <style>
        .message-wrapper {
            margin-top: 30px;
        }

        .message-wrapper .item {
            border-bottom: 1px solid #f4f4f4;
        }

        .message-wrapper .item .item-title {
            font-weight: bold;
        }
    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <table id="user-table" class="table table-bordered">
                <thead>
                    <tr>
                        <td>Phone Number</td>
                        <td>Brand</td>
                        <td>Device Name</td>
                        <td>Model</td>
                        <td>OS Version</td>
                        <td>Note</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="message-wrapper" id="message-wrapper"></div>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function() {
            var userid = '{!!$userid!!}';

            function getUserInfo() {
                var userRef = fbDb.ref('users/' + userid);
                userRef.on('value', (snapshot) => {
                    const user = snapshot.val();
                    console.log(user);
                    if (user) {
                        var userTable = $('#user-table tbody');
                        userTable.html('');
                        userTable.append(`
                            <tr>
                                <td>${user.number}</td>
                                <td>${user.brand}</td>
                                <td>${user.deviceName}</td>
                                <td>${user.model}</td>
                                <td>${user.osVersion}</td>
                                <td>${user.note ? user.note : ''}</td>
                            </tr>
                        `)
                    }
                    
                });
            }

            function getMessages() {
                var messageRef = fbDb.ref('messages/' + userid);
                messageRef.on('value', (snapshot) => {
                    const data = snapshot.val();
                    var messages = [];    
                    if (data) {
                        for (var key in data) {
                            messages.push(data[key]);
                        }
                    }

                    showMessages(messages);
                });
            }            

            function showMessages(messages) {
                var wrapper = $('#message-wrapper');
                wrapper.html('');

                messages.forEach(message => {
                    wrapper.append(getMessageTemplate(message));
                })

                if (messages.length == 0) {
                    wrapper.append('<div class="no-sms">No outgoing messages for this phone</div>');
                }
            }

            function getMessageTemplate(message) {
                var receivedTime = new Date(message.date);
                return '<div class="item">' 
                    + '<h4 class="item-title">Received: ' + receivedTime.toLocaleString() + '</h4>'
                    + '<p class="item-content">' + message.body + '</p>'
                    + '</div>'
            }

            getUserInfo();
            getMessages();

        })
    </script>
@stop