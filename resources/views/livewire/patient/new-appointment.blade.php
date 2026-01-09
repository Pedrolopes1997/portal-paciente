<div class="max-w-4xl mx-auto py-6">
    
    @if(!$successMessage)
    <div class="flex justify-between mb-8 px-4 overflow-x-auto">
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 flex items-center justify-center rounded-full border-2 {{ $step >= 0 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-400' }} font-bold text-xs mb-1">
                @if($step > 0) ✓ @else 1 @endif
            </div>
            <span class="text-xs font-bold {{ $step >= 0 ? 'text-primary-600' : 'text-gray-400' }}">Tipo</span>
        </div>

        <div class="flex-1 h-0.5 bg-gray-200 mt-4 mx-2"></div>

        <div class="flex flex-col items-center">
             <div class="w-8 h-8 flex items-center justify-center rounded-full border-2 {{ $step >= 1 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-400' }} font-bold text-xs mb-1">
                @if($step > 1) ✓ @else 2 @endif
            </div>
            <span class="text-xs font-bold {{ $step >= 1 ? 'text-primary-600' : 'text-gray-400' }}">Especialidade</span>
        </div>

        <div class="flex-1 h-0.5 bg-gray-200 mt-4 mx-2"></div>

        <div class="flex flex-col items-center">
             <div class="w-8 h-8 flex items-center justify-center rounded-full border-2 {{ $step >= 2 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-400' }} font-bold text-xs mb-1">
                @if($step > 2) ✓ @else 3 @endif
            </div>
            <span class="text-xs font-bold {{ $step >= 2 ? 'text-primary-600' : 'text-gray-400' }}">Profissional</span>
        </div>

        <div class="flex-1 h-0.5 bg-gray-200 mt-4 mx-2"></div>

        <div class="flex flex-col items-center">
             <div class="w-8 h-8 flex items-center justify-center rounded-full border-2 {{ $step >= 3 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-400' }} font-bold text-xs mb-1">
                @if($step > 3) ✓ @else 4 @endif
            </div>
            <span class="text-xs font-bold {{ $step >= 3 ? 'text-primary-600' : 'text-gray-400' }}">Data</span>
        </div>

        <div class="flex-1 h-0.5 bg-gray-200 mt-4 mx-2"></div>

        <div class="flex flex-col items-center">
             <div class="w-8 h-8 flex items-center justify-center rounded-full border-2 {{ $step >= 4 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-400' }} font-bold text-xs mb-1">
                5
            </div>
            <span class="text-xs font-bold {{ $step >= 4 ? 'text-primary-600' : 'text-gray-400' }}">Fim</span>
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 min-h-[400px]">
        
        @if($step === 0)
            <h2 class="text-xl font-semibold mb-6 text-gray-800 text-center">O que você deseja agendar?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto mt-8">
                <button wire:click="selectType('consulta')" 
                        class="flex flex-col items-center justify-center p-8 border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-lg font-bold text-gray-700 group-hover:text-primary-700">Consulta Médica</span>
                    <span class="text-sm text-gray-500 text-center mt-2">Agende com nossos especialistas</span>
                </button>

                <button wire:click="selectType('exame')" 
                        class="flex flex-col items-center justify-center p-8 border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition group">
                    <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-lg font-bold text-gray-700 group-hover:text-primary-700">Exames</span>
                    <span class="text-sm text-gray-500 text-center mt-2">Laboratório e Imagem</span>
                </button>
            </div>
        @endif

        @if($step === 1)
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $appointmentType === 'exame' ? 'Qual o exame?' : 'Qual a especialidade?' }}
                </h2>
                <button wire:click="back" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Voltar
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($specialties as $specialty)
                    <button wire:click="selectSpecialty({{ $specialty->id }})" 
                            class="p-4 border rounded-lg hover:bg-primary-50 hover:border-primary-500 transition text-left group">
                        <span class="font-medium text-gray-700 group-hover:text-primary-700">
                            {{ $specialty->name ?? $specialty->descricao }}
                        </span>
                    </button>
                @endforeach
            </div>
            
            @if(count($specialties) == 0)
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500 mb-2">
                        {{ $appointmentType === 'exame' ? 'Nenhum exame disponível no momento.' : 'Nenhuma especialidade disponível.' }}
                    </p>
                </div>
            @endif
        @endif

        @if($step === 2)
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $appointmentType === 'exame' ? 'Escolha o Profissional ou Recurso' : 'Escolha o Médico' }}
                </h2>
                <button wire:click="back" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Voltar
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($doctors as $doctor)
                    <button wire:click="selectDoctor({{ $doctor->id }})" 
                            class="flex items-center p-4 border rounded-lg hover:bg-primary-50 hover:border-primary-500 transition">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-600 font-bold">
                            {{ substr($doctor->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <div class="font-bold text-gray-800">{{ $doctor->name }}</div>
                            <div class="text-xs text-gray-500">CRM: {{ $doctor->crm ?? '---' }}</div>
                        </div>
                    </button>
                @endforeach
            </div>
            @if(count($doctors) == 0)
                <p class="text-gray-500 text-center py-4">Nenhum profissional disponível nesta especialidade.</p>
            @endif
        @endif

        @if($step === 3)
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Quando?</h2>
                <button wire:click="back" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Voltar
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecione o Dia</label>
                    <input type="date" wire:model.live="selectedDate" 
                           min="{{ date('Y-m-d') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    
                    <div wire:loading wire:target="selectedDate" class="mt-2 text-sm text-primary-600">
                        Buscando horários...
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Horários Disponíveis</label>
                    
                    @if(count($slots) > 0)
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($slots as $slot)
                                <button type="button"
                                    wire:click="$set('selectedSlot', '{{ $slot['time'] }}')"
                                    class="py-2 px-3 text-sm rounded border 
                                    {{ $selectedSlot === $slot['time'] 
                                        ? 'bg-primary-600 text-white border-primary-600' 
                                        : 'bg-white text-gray-700 hover:border-primary-500' }}">
                                    {{ $slot['time'] }}
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-md text-sm">
                            Nenhum horário livre para esta data. Tente mudar o dia no calendário ao lado.
                        </div>
                    @endif

                    @if($selectedSlot)
                        <div class="mt-8 pt-6 border-t animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observações (Opcional)</label>
                            <textarea wire:model="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm mb-4" placeholder="Ex: Sinto dor de cabeça frequente..."></textarea>

                            <button wire:click="confirmAppointment" 
                                    wire:loading.attr="disabled"
                                    class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition flex justify-center items-center shadow-lg transform hover:scale-[1.01]">
                                <span wire:loading.remove>Confirmar Agendamento</span>
                                <span wire:loading>Processando...</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($step === 4)
            <div class="text-center py-10">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Agendamento Realizado!</h2>
                <p class="text-gray-600 mb-8">Sua reserva foi confirmada com sucesso.</p>
                
                <a href="{{ route('paciente.dashboard', ['tenant_slug' => $tenant->slug]) }}" 
                   class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 font-semibold shadow-md">
                    Voltar ao Início
                </a>
            </div>
        @endif

    </div>
</div>