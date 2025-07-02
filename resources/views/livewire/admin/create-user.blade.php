<section>
    <flux:modal name="create-user" :show="$errors - > isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Add user') }}</flux:heading>

                <flux:subheading>
                    {{ __('Fill the form.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="form.name" :label="__('Name')" type="name" />
            <flux:input wire:model="form.email" :label="__('Email')" type="email" />
            <flux:input wire:model="form.section" :label="__('Section')" type="text" />
            <flux:input wire:model="form.contact" :label="__('Contact')" type="number" />

            <flux:input wire:model="form.password" :label="__('Password')" type="password" clearable />

            <flux:radio.group wire:model="form.role" label="Role">
                {{-- <flux:radio name="role" value="administrator" label="Administrator"
                    description="Administrator users can perform any action." disabled />
                <flux:radio name="role" value="verifikator" label="Verifikator"
                    description="Verifikator users have the ability to read, create, and update." /> --}}
                <flux:radio name="role" value="user" label="User"
                    description="Users only have the ability to read. Create, and update are restricted." />
            </flux:radio.group>

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
