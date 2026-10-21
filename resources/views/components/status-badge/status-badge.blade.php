@props(['status'])
<span {{ $attributes->class(['status-pill','status-'.$status]) }}>{{ ucfirst(str_replace('_',' ',$status)) }}</span>
