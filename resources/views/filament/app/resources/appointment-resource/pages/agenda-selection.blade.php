<x-filament-panels::page>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2 text-primary-600">
                    <x-filament::icon
                        icon="heroicon-m-user-group"
                        class="h-6 w-6"
                    />
                    <span class="font-bold text-lg text-gray-900">Agenda de Consulta</span>
                </div>
            </x-slot>

            <x-slot name="description">
                Selecione o médico para visualizar os horários disponíveis.
            </x-slot>

            <div class="space-y-4 pt-2">
                <div>
                    <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        Selecione o Médico
                    </label>
                    <div class="mt-2">
                        <select wire:model="doctor_id" class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 sm:text-sm">
                            <option value="">Clique para selecionar...</option>
                            @foreach($doctors_list as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <x-filament::button wire:click="irParaConsultas" class="w-full" size="lg">
                        Abrir Agenda Médica
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>


        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2" style="color: #9333ea;"> <x-filament::icon
                        icon="heroicon-m-beaker"
                        class="h-6 w-6"
                    />
                    <span class="font-bold text-lg text-gray-900">Agenda de Exames</span>
                </div>
            </x-slot>

            <x-slot name="description">
                Selecione o tipo de exame para ver a disponibilidade.
            </x-slot>

            <div class="space-y-4 pt-2">
                <div>
                    <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        Selecione o Exame
                    </label>
                    <div class="mt-2">
                        <select wire:model="specialty_id" class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:border-purple-500 focus:ring-1 focus:ring-inset focus:ring-purple-500 sm:text-sm">
                            <option value="">Clique para selecionar...</option>
                            @foreach($specialties_list as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button wire:click="irParaExames" 
                            type="button"
                            style="background-color: #9333ea; color: white;"
                            class="w-full flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold shadow-sm hover:opacity-90 transition-opacity">
                        Abrir Agenda de Exames
                    </button>
                </div>
            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>