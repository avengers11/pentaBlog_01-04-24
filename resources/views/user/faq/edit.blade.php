<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Update_FAQ'] ?? __('Update FAQ') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('user.faq_management.update_faq') }}"
                    method="post">
                    @csrf
                    <input type="hidden" name="faq_id" id="in_id">
                    <div class="form-group">
                        <label for="">{{ $keywords['Question'] ?? __('Question*') }}</label>
                        <input type="text" id="in_question" class="form-control" name="question"
                            placeholder="{{ $keywords['Enter_Question'] ?? __('Enter Question') }}">
                        <p id="eerrquestion" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Answer'] ?? __('Answer*') }}</label>
                        <textarea class="form-control" id="in_answer" name="answer" rows="5" cols="80"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter FAQ Serial Number') }}"></textarea>
                        <p id="eerranswer" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number*') }}</label>
                        <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter FAQ Serial Number') }}">
                        <p id="eerrserial_number" class="mt-1 mb-0 text-danger em"></p>
                        <p class="text-warning mt-2">
                            <small>{{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the Item will be shown.') }}</small>
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ $keywords['Close'] ?? __('Close') }}
                </button>
                <button id="updateBtn" type="button" class="btn btn-primary">
                    {{ $keywords['Update'] ?? __('Update') }}
                </button>
            </div>
        </div>
    </div>
</div>
