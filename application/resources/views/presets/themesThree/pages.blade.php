@extends($activeTemplate.'layouts.frontend')

@section('content')

    @if($sections && $sections->secs != null)
        @foreach($sections->visibleSections() as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
