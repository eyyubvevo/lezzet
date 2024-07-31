<div>
    <a 
      href="@if($count > 0) {{ route(app()->getLocale().'.shoppingCart')}} @else # @endif"
    >
        <div class="header-basket">
            <i class="fas fa-store fa-lg"></i>
            <span class="count-basket">{{ $count}}</span>
        </div>
    </a>
</div>
