@pushOnce('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      @foreach (session()->only(['success', 'error', 'warning', 'info']) as $type => $message)
        window.dispatchEvent(new CustomEvent('toasts:create', {
          detail: {
            type: '{{ $type }}',
            title: '{!! addslashes($message) !!}'
          }
        }));
      @endforeach

      @if ($errors->any())
        @foreach ($errors->all() as $error)
          @php
              $displayError = $error === 'validation.required' ? 'Please complete all required fields.' : $error;
          @endphp
          window.dispatchEvent(new CustomEvent('toasts:create', {
            detail: {
              type: 'error',
              title: '{!! addslashes($displayError) !!}'
            }
          }));
        @endforeach
      @endif
    });
  </script>
@endpushOnce

<div x-data x-toasts="{placement: 'top-center'}">
  <template x-teleport="body">
    <template x-for="placement in placements" x-bind:key="placement">
      <div x-toasts:group="placement"
        class="fixed z-50 space-y-2 data-[align=center]:left-1/2 data-[align=end]:right-4 data-[align=start]:left-4 data-[side=bottom]:bottom-4 data-[side=top]:top-4 data-[align=center]:-translate-x-1/2"
      >
        <template x-for="toast in $group.toasts()" x-bind:key="toast.id">
          <div
            x-toasts:toast="toast"
            class="bg-background relative w-[400px] max-w-[90vw] overflow-hidden rounded-xl border p-4 shadow-xl ring-1 ring-black/5"
            x-bind:class="{
                'data-[state=open]:animate-slide-in-down data-[state=closed]:animate-slide-out-up': $toast.placement.includes('top'),
                'data-[state=open]:animate-slide-in-up data-[state=closed]:animate-slide-out-down': $toast.placement.includes('bottom'),
                'border-info': $toast.type === 'info',
                'border-success': $toast.type === 'success',
                'border-warning': $toast.type === 'warning',
                'border-danger': $toast.type === 'error'
            }"
          >
            <div class="flex items-start gap-4 pr-6">
              <div class="flex-shrink-0 pt-0.5">
                <template x-if="$toast.type === 'error'">
                  <svg class="h-5 w-5 text-danger" style="color: #ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="$toast.type === 'success'">
                  <svg class="h-5 w-5 text-success" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
              </div>
              <div class="flex-1">
                <h3 class="text-sm font-medium text-zinc-900" x-text="$toast.title"></h3>
                <template x-if="$toast.description">
                  <p class="mt-1 text-sm text-zinc-500" x-text="$toast.description"></p>
                </template>
              </div>
            </div>
            
            <button
              x-toasts:toast-close-trigger
              class="absolute right-2 top-2 rounded-lg p-1.5 text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-200"
            >
              <span class="sr-only">@lang('visual-debut::shop.close')</span>
              <x-lucide-x class="h-4 w-4" />
            </button>
          </div>
        </template>
      </div>
    </template>
  </template>
</div>
