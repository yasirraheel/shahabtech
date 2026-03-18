    @forelse($items as $loop=>$item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td><a href="{{ route('service.details', ['slug' => slug($item->title), 'id' => $item->id])}}">{{__($item->title)}}</a></td>
        <td>{{$general->cur_sym}} {{showAmount($item->price) }}</td>
        <td> @php echo $item->icon; @endphp</td>
        <td>{{ showDateTime($item->created_at) }}</td>
        <td>
            @php
                echo $item->statusBadge;
            @endphp
        </td>
        <td>
            <div class="d-flex justify-content-end align-items-center gap-2">
                <div class="form-group mb-0">
                    <label class="switch m-0" title="@lang('Change Status')">
                        <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.service.status', $item->id) }}"
                        data-question="@lang('Are you sure to change service status from this system?')" @checked($item->status)>
                        <span class="slider round"></span>
                    </label>
                </div>

                <a href="{{route('admin.service.edit',$item->id)}}" title="@lang('Edit')" class="btn btn-sm editBtn">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                <button title="@lang('Delete')" type="button"
                    class="btn btn-sm btn--danger confirmationBtn"
                    data-action="{{ route('admin.service.delete', $item->id) }}"
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


