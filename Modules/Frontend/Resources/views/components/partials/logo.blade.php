<div class="logo-default">
    <a class="navbar-brand text-primary" href="{{route('login')}}">

        @php

            $logo=GetSettingValue('dark_logo') ??  asset(setting('dark_logo'));
        @endphp


        <img class="img-fluid logo" src="{{ $logo }}" alt="streamit">
    </a>
</div>
