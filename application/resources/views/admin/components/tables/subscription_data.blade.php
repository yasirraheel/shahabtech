@forelse($items as $loop=>$subscription)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td class="user--td">
        <div class="d-flex justify-content-between justify-content-lg-start gap-3">
            <div class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                <div class="user--thumb-two">
                    @if(!empty($subscription?->user?->image))
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $subscription?->user?->image ) }}" alt="@lang('Image')">
                    @else
                        <img src="{{ getImage('assets/images/general/avatar.png') }}" alt="@lang('Image')">
                    @endif
                </div>
                <div class="user--content">
                    <a class="text-start text-dark" href="{{ appendQuery('search', $subscription?->user->username) }}">
                        {{ $subscription?->user->fullname }}
                    </a>
                    <br>
                    <a href="{{ route('admin.users.detail', $subscription?->user_id) }}" class="text-start">{{ '@'.__($subscription?->user?->username) }}</a>
                </div>
            </div>
        </div>
    </td>

    <td><a href="{{route('plans')}}">{{__($subscription?->plan?->name ?? '')}}</a></td>
    <td>{{showDateTime($subscription->created_at)}}</td>
    <td>
        @if($subscription->ends_at >= now())
        <span class="badge badge--success">@lang('Active')</span>
        @else
        <span class="badge badge--danger">@lang('Expired')</span>
        @endif
    </td>
    <td>{{ showDateTime($subscription->ends_at, 'd M Y H:i') }}</td>
</tr>
@empty
<tr>
    <td class="text-muted text-center" colspan="100%" data-label="@lang('Subscription List')">{{ __($emptyMessage) }}</td>
</tr>
@endforelse


