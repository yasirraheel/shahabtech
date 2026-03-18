@forelse($items as $loop=>$item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{__($item->title) }}</td>

    <td>
        <img src="{{getImage(getFilePath('portfolioImage').'/'. $item->image)}}" class="rounded" alt="portfolio image" style="width:50px;">
    </td>

    <td>
        @php
            echo $item->statusBadge;
        @endphp
    </td>

    <td>
        <div class="d-flex justify-content-end align-items-center gap-2">
            <div class="form-group mb-0">
                <label class="switch m-0" title="@lang('Change Status')">
                    <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.portfolio.status', $item->id) }}"
                    data-question="@lang('Are you sure to change portfolio status from this system?')" @checked($item->status)>
                    <span class="slider round"></span>
                </label>
            </div>

            <a href="{{route('admin.portfolio.edit',$item->id)}}" title="@lang('Edit')" class="btn btn-sm editBtn">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>

            <button title="@lang('Delete')" type="button"
                class="btn btn-sm btn--danger confirmationBtn"
                data-action="{{ route('admin.portfolio.delete', $item->id) }}"
                data-question="@lang('Are you sure to delete this role?')">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </td>

</tr>
@empty
<tr>
    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
</tr>
@endforelse
