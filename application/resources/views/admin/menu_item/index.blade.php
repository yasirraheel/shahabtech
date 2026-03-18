@extends('admin.layouts.app')
@section('panel')


<div class="row gy-4 justify-content-between mb-3 pb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="d-flex flex-wrap justify-content-start w-100">
            <form class="form-inline w-100">
                <div class="search-input--wrap position-relative">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Search title')" value="{{ request()->search }}">
                    <button class="search--btn position-absolute" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.L')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Url')</th>
                                <th>@lang('Tempname')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="items_table__body">
                            @forelse($items as $data)
                            <tr>
                                <td class="user--td">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ __($data->title) }}
                                </td>

                                <td>
                                    {{ $data->url }}
                                </td>

                                <td>
                                    {{ $data->tempname }}
                                </td>

                                <td>
                                    @php echo $data->statusBadge; @endphp
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <div class="form-group mb-0">
                                            <label class="switch m-0" title="@lang('Change Status')">
                                                <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.menuitem.status', $data->id) }}"
                                                data-question="@lang('Are you sure to change menu item status from this system?')" @checked($data->status)>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <button type="button" title="@lang('Edit')" class="btn btn-sm edit" data-title="{{$data->title}}" data-url="{{$data->url}}" data-linktype="{{$data->link_type}}" data-pageid="{{$data->page_id}}"  data-action="{{ route('admin.menuitem.storeorupdate',$data->id) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <button title="@lang('Delete')" type="button"
                                            class="btn btn-sm btn--danger confirmationBtn"
                                            data-action="{{ route('admin.menuitem.delete',$data->id) }}"
                                            data-question="@lang('Are you sure to delete this item menu?')">
                                           <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="pagination-wrapper" class="pagination__wrapper py-4 {{ $items->hasPages() ? '' : 'd-none' }}">
                @if ($items->hasPages())
                {{ paginateLinks($items) }}
                @endif
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="menuForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="bulkActionModalLabel" class="modal-title">@lang('Add new menu')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> @lang('Link Type')</label>
                        <select class="select2-basic form-control form-select" name="link_type">
                            <option value="1" {{ old('1') ? 'selected' : '' }}>@lang('System Link')</option>
                            <option value="2" {{ old('2') ? 'selected' : '' }}>@lang('External URL Link')</option>
                            <option value="3" {{ old('3') ? 'selected' : '' }}>@lang('Page Link')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="required">@lang('URL')</label>
                        <input class="form-control" type="text" name="url" required value="{{ old('url') }}">
                    </div>

                    <div class="form-group">
                        <label> @lang('Pages')</label>
                        <select class="select2-basic form-control form-select" name="page_id">
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}" {{ old('page_id') == $page->id ? 'selected' : '' }}>{{ __($page->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="required"> @lang('Title')</label>
                        <input class="form-control" type="text" name="title" required value="{{ old('title') }}">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 submit_btn">@lang('Save')</button>
                </div>
            </div>
        </form>
    </div>
</div>


<x-confirmation-modal></x-confirmation-modal>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn--primary addModal" data-toggle="modal" data-action="{{ route('admin.menuitem.storeorupdate') }}"><i class="fa-solid fa-plus"></i> @lang('Add Item')</a>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            $('.addModal').on('click', function () {
                let action = $(this).data('action');
                $('#menuForm')[0].reset();
                $('#menuForm').attr('action', action);
                $('#menuModal .modal-title').text("@lang('Add New Item')");
                $('#menuModal .submit_btn').text("@lang('Submit')");
                $('#menuModal').modal('show');
            });


            function toggleFields() {
                var linkType = $('select[name="link_type"]').val();

                if (linkType === '1' || linkType === '2') {
                    $('input[name="url"]').closest('.form-group').show().find('input').prop('required', true);
                    $('select[name="page_id"]').closest('.form-group').hide().find('select').prop('required', false).val('');

                    $('input[name="title"]').closest('.form-group').show().find('input').prop('required', true);
                } else if (linkType === '3') {
                    $('input[name="url"]').closest('.form-group').hide().find('input').prop('required', false).val('');
                    $('input[name="title"]').closest('.form-group').hide().find('input').prop('required', false).val('');

                    $('select[name="page_id"]').closest('.form-group').show().find('select').prop('required', true);
                }
            }

            toggleFields();

            $('select[name="link_type"]').on('change',function () {
                toggleFields();
            });

            $('.edit').on('click', function () {
                let modal = $('#menuModal');
                let action = $(this).data('action');

                $('#menuForm')[0].reset();
                $('#menuForm').attr('action', action);
                $('#menuModal .modal-title').text("@lang('Update Item')");
                $('#menuModal .submit_btn').text("@lang('Update')");

                modal.find('input[name=title]').val($(this).data('title'));
                modal.find('input[name=url]').val($(this).data('url'));
                modal.find('select[name=link_type]').val($(this).data('linktype')).trigger('change');
                modal.find('select[name=page_id]').val($(this).data('pageid')).trigger('change');

                $('#menuModal').modal('show');
            });

        })(jQuery);

    </script>
@endpush
