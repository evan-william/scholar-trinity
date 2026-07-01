@php($currentLocale = session('locale', str_replace('_', '-', app()->getLocale())))
<form method="GET" action="{{ route('locale.switch', ['locale' => 'en']) }}" class="language-switcher" data-language-switcher>
    <input type="hidden" name="redirect" value="{{ url()->current() }}">
    <label>
        <span class="sr-only">{{ __('ap_registration.language.label') }}</span>
        <select aria-label="{{ __('ap_registration.language.label') }}" onchange="this.form.action='{{ url('/locale') }}/'+this.value; this.form.submit();">
            <option value="en" @selected($currentLocale === 'en')>{{ __('ap_registration.language.english') }}</option>
            <option value="zh-TW" @selected($currentLocale === 'zh-TW')>{{ __('ap_registration.language.traditional_chinese') }}</option>
        </select>
    </label>
</form>
