@props([
    'tab',
    'activeTab',
    'label'
])

<button @click="goTo('{{ $tab }}')"
    :class="{
        'text-blue-600 border-blue-600 dark:border-blue-600': activeTab === '{{ $tab }}',
        'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': activeTab !== '{{ $tab }}'
    }"
    class="py-4 px-2 text-sm font-medium border-b-2 whitespace-nowrap cursor-pointer focus:outline-none transition-colors duration-200">
    {{ $label }}
</button>