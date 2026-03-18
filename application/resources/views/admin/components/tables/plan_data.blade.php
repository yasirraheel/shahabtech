@forelse($items as $loop => $plan)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $plan->name }}</td>

        <td>{{ showAmount($plan->price) }}</td>
        <td>
            {{ $plan->type == 1 ? __('Monthly') : __('Yearly') }}
        </td>
        <td>{{ showDateTime($plan->created_at) }}</td>

        <td>
            @php echo $plan->statusBadge($plan->status); @endphp
        </td>

        <td>
            <div class="d-flex justify-content-end align-items-center gap-2">
                <div class="form-group mb-0">
                    <label class="switch m-0" title="@lang('Change Status')">
                        <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.plan.status', $plan->id) }}"
                        data-question="@lang('Are you sure to change role status from this system?')" @checked($plan->status)>
                        <span class="slider round"></span>
                    </label>
                </div>

                <a href="{{route('admin.plan.edit',$plan->id)}}" title="@lang('Edit')" class="btn btn-sm editBtn">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                <button title="@lang('Delete')" type="button"
                    class="btn btn-sm btn--danger confirmationBtn"
                    data-action="{{ route('admin.plan.delete', $plan->id) }}"
                    data-question="@lang('Are you sure to delete this plan from the system?')">
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

