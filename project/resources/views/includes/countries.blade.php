@php
$tnOnly = filter_var(env('TN_ONLY_MODE', false), FILTER_VALIDATE_BOOLEAN);
$countriesQuery = App\Models\Country::where('status', 1);
if ($tnOnly) {
    $countriesQuery->whereIn('country_name', ['Tunisia', 'Tunisie']);
}
$countries = $countriesQuery->get();
@endphp

<option value="" disabled {{ $countries->count() === 1 ? '' : 'selected' }}>{{ __('Select Country') }}</option>
@foreach ($countries as $data)
@php
$selected = Auth::check() && Auth::user()->country == $data->country_name;
if (!$selected && $tnOnly && $countries->count() === 1) {
    $selected = true;
}
@endphp
<option value="{{ $data->country_name }}" data="{{ $data->id }}" rel5="{{ Auth::check() && Auth::user()->country == $data->country_name ? 1 : 0 }}" rel="{{ $data->states->count() > 0 ? 1 : 0 }}" rel1="{{ Auth::check() ? 1 : 0 }}" rel2="{{ Auth::check() && Auth::user()->state ? Auth::user()->state : 0 }}" {{ $selected ? 'selected' : '' }} data-href="{{ route('country.wise.state', $data->id) }}">{{ $data->country_name }}</option>
@endforeach
