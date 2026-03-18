@extends('admin.layouts.app')
@section('panel')
<div class="row gy-4 justify-content-between mb-3 pb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="d-flex flex-wrap justify-content-start w-100">
            <form class="form-inline w-100">
                <div class="search-input--wrap position-relative">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Search menu')" value="{{ request()->search }}">
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
                                <th>@lang('Menu')</th>
                                <th>@lang('Menu Items')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="items_table__body">
                            @forelse($menus as $data)
                            <tr>
                                <td class="user--td">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ __($data->name) }}
                                </td>

                                <td>
                                    {{ $data->items_count }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a href="{{ route('admin.menu.assign.item', $data->id) }}" class="btn btn-sm" title="@lang('Assign Items')">
                                            <i class="fa-solid fa-indent"></i>
                                        </a>
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
        </div>
    </div>
</div>




@endsection






