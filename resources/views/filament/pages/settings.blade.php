<x-filament::page>
	<x-filament-panels::form :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()" wire:submit="save">
		{{ $this->form }}

		<x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
	</x-filament-panels::form>

	<x-filament-panels::page.unsaved-data-changes-alert />
</x-filament::page>
