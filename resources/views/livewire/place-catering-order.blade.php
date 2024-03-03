<div class="flex-col sm:flex">
    <div class="p-10 border-2 border-black dark:border-white m-10 dark:text-black">
        <form wire:submit="submitAndCreateOrder">
            {{ $this->form }}

            <button type="submit" id="card-button" class="rounded bg-black px-6 py-4 text-white mt-4">
                Submit My Order
            </button>
        </form>
        <x-filament-actions::modals/>
    </div>
</div>
