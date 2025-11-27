<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои семейные деревья') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('family-trees.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Создать новое древо
                        </a>
                        <button id="importGedcomBtn" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Загрузить GEDCOM
                        </button>
                        <!-- Скрытая форма для загрузки файла -->
                        <form id="importGedcomForm" action="{{ route('family-trees.import-gedcom') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" name="gedcom_file" id="gedcomFileInput" accept=".ged">
                            <input type="hidden" name="family_tree_id" id="familyTreeIdInput" value="">
                            <button type="submit">Загрузить</button>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($familyTrees as $tree)
                            <div class="border rounded-lg p-4 shadow-sm">
                                <h3 class="text-lg font-semibold mb-2">{{ $tree->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $tree->description }}</p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('family-trees.show', $tree) }}" 
                                       class="text-blue-600 hover:text-blue-800">Открыть</a>
                                    <a href="{{ route('family-trees.export-gedcom', $tree) }}" 
                                       class="text-green-600 hover:text-green-800">Экспорт GEDCOM</a>
                                    <form action="{{ route('family-trees.destroy', $tree) }}" method="POST" onsubmit="return confirm('Удалить это древо?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($familyTrees->isEmpty())
                        <p class="text-gray-500">У вас пока нет созданных деревьев.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('importGedcomBtn').addEventListener('click', function() {
            // Здесь можно показать модальное окно для выбора дерева или создать новое
            // Для простоты создадим новое дерево с именем "Импортированное из GEDCOM"
            let treeName = prompt("Введите название для нового дерева (на основе GEDCOM файла):", "Импортированное из GEDCOM");
            if (treeName) {
                // Создаем новое дерево через AJAX
                fetch('{{ route("family-trees.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: treeName,
                        description: 'Импортировано из GEDCOM файла'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.tree_id) {
                        // Устанавливаем ID созданного дерева в скрытое поле
                        document.getElementById('familyTreeIdInput').value = data.tree_id;
                        // Триггерим клик на скрытом input file
                        document.getElementById('gedcomFileInput').click();
                    }
                });
            }
        });

        // Когда файл выбран, автоматически отправляем форму
        document.getElementById('gedcomFileInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('importGedcomForm').submit();
            }
        });
    </script>
</x-app-layout>