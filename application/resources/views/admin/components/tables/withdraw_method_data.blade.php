@forelse($items as $method)
    <tr>
        <td>
            <div class="d-flex align-items-center justify-content-start gap-3">
                <div class="gateway--thumb">
                    <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image ) }}" alt="@lang('Image')">
                </div>

                <h6>
                    {{ __($method->name ?? '') }}
                </h6>
            </div>
        </td>

        <td class="fw-semibold">{{ __($method->currency) }}</td>

        <td class="fw-semibold">{{ showAmount($method->fixed_charge) }}
            {{ __($general->cur_text) }}
            {{ 0 < $method->percent_charge ? ' + ' . showAmount($method->percent_charge) . ' %' : '' }}
        </td>

        <td class="fw-semibold">
            {{ $method->min_limit + 0 }} - {{ $method->max_limit + 0 }} {{ __($general->cur_text) }}
        </td>

        <td>
            @php echo $method->statusBadge; @endphp
        </td>

        <td>
            <div class="d-flex justify-content-end align-items-center gap-2">
                <div class="form-group mb-0">
                    <label class="switch m-0" title="@lang('Change Status')">
                        <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.withdraw.method.status',$method->id) }}"
                        data-question="@lang('Are you sure to change method status from this system?')" @checked($method->status)>
                        <span class="slider round"></span>
                    </label>
                </div>

                <a title="@lang('Edit')" href="{{ route('admin.withdraw.method.edit', $method->id) }}" class="edit--btn btn btn--sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
