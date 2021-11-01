<div id="{{ str_replace(' ', '', lcfirst($subject)) }}DeleteModal" data-target="" class="modal fade bs-modal-sm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form class="submit_form_response" action="{{ $route }}" method="post">
                @csrf
                <input type="hidden" name="id" id="delete_{{ str_replace(' ', '', lcfirst($subject)) }}_id">
                <div class="modal-header alert alert-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete {{ $subject }}</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Do you really want to delete the selected {{ mb_strtolower($subject) }}?
                        @if(isset($message))
                            <br/>{{ $message }}
                        @endif
                    </p>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
