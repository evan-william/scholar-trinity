@props([
    'label' => 'Actions',
])

<details class="action-menu">
    <summary aria-label="{{ $label }}" title="{{ $label }}">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
    </summary>
    <div class="action-menu-panel">
        {{ $slot }}
    </div>
</details>
