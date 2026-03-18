@forelse($items as $trx)
    <tr>
        <td class="user--td">
            <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                <div class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                    <div class="user--thumb-two">
                        @if(!empty($trx?->user?->image))
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $trx?->user?->image ) }}" alt="@lang('Image')">
                        @else
                            <img src="{{ getImage('assets/images/general/avatar.png') }}" alt="@lang('Image')">
                        @endif
                    </div>
                    <div class="user--content">
                        <a class="text-start text-dark" href="{{ appendQuery('search', $trx->user->username) }}">
                            {{ $trx->user->fullname }}
                        </a>
                        <br>
                        <a href="{{ route('admin.users.detail', $trx?->user_id) }}" class="text-start">{{ '@'.__($trx?->user?->username) }}</a>
                    </div>
                </div>
            </div>
        </td>

        <td>
            <strong>{{ $trx->trx }}</strong>
        </td>

        <td>
            {{ showDateTime($trx->created_at) }}
        </td>

        <td class="budget">
            <span class="fw--500 {{ $trx->trx_type == '+' ? 'text--success' : 'text--danger' }}">
                {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ $general->cur_text }}
            </span>
        </td>

        <td class="budget">
            {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
        </td>

        <td>{{ __($trx->details) }}</td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
