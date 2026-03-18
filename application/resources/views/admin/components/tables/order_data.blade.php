@forelse($items as $loop=>$order)
    <tr>
        <td>{{ $loop->iteration }}</td>

        <td class="user--td">
            <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                <div class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                    <div class="user--thumb-two">
                        @if(!empty($order?->user?->image))
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $order?->user?->image ) }}" alt="@lang('Image')">
                        @else
                            <img src="{{ getImage('assets/images/general/avatar.png') }}" alt="@lang('Image')">
                        @endif
                    </div>
                    <div class="user--content">
                        <a class="text-start text-dark" href="{{ appendQuery('search', $order?->user?->username) }}">
                            {{ $order?->user?->fullname ?? 'N/A' }}
                        </a>
                        <br>
                        <a href="{{ route('admin.users.detail', $order?->user_id) }}" class="text-start">{{ '@'.__($order?->user?->username ?? 'N/A') }}</a>
                    </div>
                </div>
            </div>
        </td>

        <td><a href="{{route('admin.service.index')}}">{{__($order?->service?->title)}}</a></td>
        <td>#{{$order->order_number}}</td>
        <td>{{$general->cur_sym}}{{showAmount($order->service_price, 2)}}</td>
        <td>{{ showDateTime($order->created_at)}}</td>
        <td>@php echo $order->statusBadge($order->status) @endphp</td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
    </tr>
@endforelse

