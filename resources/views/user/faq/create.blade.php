<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_FAQ'] ?? __('Add FAQ') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('user.faq_management.store_faq') }}"
                    method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">{{ $keywords['Language'] ?? __('Language') }}*</label>
                        <select name="user_language_id" class="form-control" id="gallery_language">
                            <option selected disabled>{{ $keywords['Select_a_Language'] ?? __('Select a Language') }}
                            </option>
                            @foreach ($langs as $lang)
                                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                        <p id="erruser_language_id" class="mt-1 mb-0 text-danger em"></p>
                    </div>


                    <div class="form-group">
                        <label for="">{{ $keywords['Question'] ?? __('Question*') }}</label>
                        <input type="text" class="form-control" name="question"
                            placeholder="{{ $keywords['Enter_Question'] ?? __('Enter Question') }}">
                        <p id="errquestion" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Answer'] ?? __('Answer') }}*</label>
                        <textarea class="form-control" name="answer" rows="5" cols="80"
                            placeholder="{{ $keywords['Enter_Answer'] ?? __('Enter Answer') }}"></textarea>
                        <p id="erranswer" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}*</label>
                        <input type="number" class="form-control ltr" name="serial_number"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter FAQ Serial Number') }}">
                        <p id="errserial_number" class="mt-1 mb-0 text-danger em"></p>
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
                <button id="submitBtn" type="button" class="btn btn-primary">
                    {{ $keywords['Save'] ?? __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
