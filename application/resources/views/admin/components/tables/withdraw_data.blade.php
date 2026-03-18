@forelse($items as $withdraw)
    @php
        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
    @endphp
    <tr>
        <td class="user--td">
            <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                <div
                    class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                    <div class="user--thumb-two">
                        @if(!empty($withdraw->user->image))
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $withdraw?->user?->image ) }}" alt="@lang('Image')">
                        @else
                            <img src="{{ getImage('assets/images/general/avatar.png') }}" alt="@lang('Image')">
                        @endif
                    </div>
                    <div class="user--content">
                        <a class="text-start text-dark"
                            href="{{ appendQuery('search', $withdraw->user->username ?? null) }}">{{ __($withdraw->user->fullname ?? 'N/A') }}

                        </a>
                        <p><a href="{{ route('admin.users.detail', $withdraw->user_id) }}">{{ '@'.__($withdraw->user->username ?? 'N/A') }}</a></p>

                    </div>
                </div>
            </div>
        </td>

        <td>
            {{ $withdraw->method->name ?? 'N/A' }}
            <br>
            <span class="fw--500">{{ $withdraw->trx }}</span>
        </td>


        <td>
            <span title="@lang('Amount after charge')">
                {{ showAmount($withdraw->amount - $withdraw->charge) }}{{ __($general->cur_text) }}
            </span><br>
            <span class="fw--500" title="@lang('Charge')">
                {{ showAmount($withdraw->charge) }}{{ __($general->cur_text) }}
            </span>

        </td>

        <td>
            {{ showDateTime($withdraw->created_at) }}
        </td>

        <td>
            @php echo $withdraw->statusBadge @endphp
        </td>
        <td>
            <a title="@lang('Details')"
                href="{{ route('admin.withdraw.details', $withdraw->id) }}"
                class="btn btn-sm ms-1">
                <i class="fa-solid fa-eye"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
