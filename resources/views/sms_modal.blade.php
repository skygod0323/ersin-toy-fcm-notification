<div class="modal fade" tabindex="-1" role="dialog" id="sms_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Send SMS</h3>
      </div>
      <div class="modal-body">
        <div class="">
            <div class="form-group">
                <input id="modal_phone" placeholder="Please input phone number" class="form-control">                
            </div>
            <div class="form-group">
                <textarea id="modal_sms_text" class="form-control" placeholder="Please input sms message what you want to send"></textarea>
            </div>
            <input type="hidden" id="modal_userid">
            <input type="hidden" id="modal_notificationid">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_send_sms">Send SMS</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>