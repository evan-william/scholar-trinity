@props([
    'label' => 'Actions',
])

<details class="action-menu">
    <summary aria-label="{{ $label }}" title="{{ $label }}">
        <span aria-hidden="true">...</span>
    </summary>
    <div class="action-menu-panel">
        {{ $slot }}
    </div>
</details>
