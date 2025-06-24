{{-- resources/views/livewire-tables/bootstrap-4/datatable.blade.php --}}
<div>
    @if ($component->hasConfigurableAreaFor('before-toolbar'))
    @include($component->getConfigurableAreaFor('before-toolbar'), $component->getParametersForConfigurableArea('before-toolbar'))
    @endif

    <div class="mb-4 d-md-flex justify-content-between">
        <div class="mb-3 mb-md-0">
            @if ($component->searchIsEnabled() && $component->searchVisibilityIsEnabled())
            <div class="mb-3 mb-md-0 input-group">
                <input
                    wire:model{{ $component->getSearchOptions() }}="search"
                    placeholder="{{ $component->getSearchPlaceholder() }}"
                    type="text"
                    class="form-control">
            </div>
            @endif
        </div>

        <div class="d-flex">
            @if ($component->hasConfigurableAreaFor('toolbar-left-start'))
            @include($component->getConfigurableAreaFor('toolbar-left-start'), $component->getParametersForConfigurableArea('toolbar-left-start'))
            @endif

            @if ($component->reorderIsEnabled())
            <button
                wire:click="{{ $component->currentlyReorderingIsEnabled() ? 'disableReordering' : 'enableReordering' }}"
                type="button"
                class="btn btn-outline-secondary d-block d-md-inline mb-md-0 mb-3 mr-md-2 mr-0">
                @if ($component->currentlyReorderingIsEnabled())
                @lang('Done')
                @else
                @lang('Reorder')
                @endif
            </button>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-left-end'))
            @include($component->getConfigurableAreaFor('toolbar-left-end'), $component->getParametersForConfigurableArea('toolbar-left-end'))
            @endif

            @if ($component->columnSelectIsEnabled())
            @include('livewire-tables::includes.column-select')
            @endif

            @if ($component->paginationIsEnabled() && $component->perPageVisibilityIsEnabled())
            <div class="ml-md-2">
                <select wire:model="perPage" id="perPage" class="form-control">
                    @foreach ($component->getPerPageAccepted() as $item)
                    <option value="{{ $item }}" wire:key="per-page-{{ $item }}">{{ $item === -1 ? 'All' : $item }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-right-start'))
            @include($component->getConfigurableAreaFor('toolbar-right-start'), $component->getParametersForConfigurableArea('toolbar-right-start'))
            @endif

            @if ($component->filterIsEnabled() && $component->filterVisibilityIsEnabled() && $component->hasVisibleFilters())
            @include('livewire-tables::includes.filter-popover')
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-right-end'))
            @include($component->getConfigurableAreaFor('toolbar-right-end'), $component->getParametersForConfigurableArea('toolbar-right-end'))
            @endif
        </div>
    </div>

    @if ($component->hasConfigurableAreaFor('before-table'))
    @include($component->getConfigurableAreaFor('before-table'), $component->getParametersForConfigurableArea('before-table'))
    @endif

    @if ($component->currentlyReorderingIsEnabled())
    <div wire:sortable="reorder">
        @endif

        <div class="table-responsive">
            <table {{ $component->getTableAttributeBag() }}>
                <thead {{ $component->getTheadAttributeBag() }}>
                    <tr {{ $component->getTrAttributeBag() }}>
                        @if ($component->currentlyReorderingIsEnabled())
                        <th>@lang('Sort')</th>
                        @endif

                        @foreach($component->getColumns() as $column)
                        @continue($column->isHidden())
                        @continue($component->columnSelectIsEnabled() && ! $component->isColumnSelectEnabled($column))

                        <th {{ $column->getHeaderAttributeBag() }}>
                            @if ($component->isSortingEnabled() && $column->isSortable() && !$column->isLabel())
                            <button
                                wire:click="sortBy('{{ $column->getColumnSelectName() }}')"
                                class="d-flex align-items-center text-left w-100 bg-transparent border-0 p-0
                                           {{ $component->getSort() === $column->getColumnSelectName() ? 
                                              ($component->getDirection() === 'asc' ? 'sort-asc' : 'sort-desc') : '' }}"
                                style="outline: none;">
                                {!! $column->getTitle() !!}
                            </button>
                            @else
                            {!! $column->getTitle() !!}
                            @endif

                            @if($column->getColumnSelectName() === 'status')
                            <button wire:click="toggleStatusFilter"
                                class="ml-2 text-muted"
                                style="background: none; border: none; outline: none;">
                                <flux:icon name="adjustments-vertical" class="w-4 h-4" />
                            </button>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody {{ $component->getTbodyAttributeBag() }}>
                    @if ($component->currentlyReorderingIsEnabled())
                    @foreach($component->getRows() as $row)
                    <tr wire:sortable.item="{{ $row->{$component->getPrimaryKey()} }}" wire:key="row-{{ $row->{$component->getPrimaryKey()} }}">
                        <td wire:sortable.handle class="text-center" style="cursor: grab;">
                            <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6-6-6z"></path>
                            </svg>
                        </td>
                        @foreach($component->getColumns() as $column)
                        @continue($column->isHidden())
                        @continue($component->columnSelectIsEnabled() && ! $component->isColumnSelectEnabled($column))

                        <td {{ $column->getTdAttributeBag($row) }}>
                            {!! $column->renderCell($row) !!}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @else
                    @forelse ($component->getRows() as $row)
                    <tr
                        wire:loading.class.delay="opacity-50"
                        wire:key="row-{{ $row->{$component->getPrimaryKey()} }}"
                        @if($component->getTableRowUrl($row))
                        onclick="window.location='{{ $component->getTableRowUrl($row) }}'"
                        style="cursor: pointer;"
                        @endif
                        class="hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                        @foreach($component->getColumns() as $column)
                        @continue($column->isHidden())
                        @continue($component->columnSelectIsEnabled() && ! $component->isColumnSelectEnabled($column))

                        <td {{ $column->getTdAttributeBag($row) }}>
                            @if($column->isCheckbox())
                            <div onclick="event.stopPropagation();">
                                {!! $column->renderCell($row) !!}
                            </div>
                            @elseif($column->isButtonGroup())
                            <div onclick="event.stopPropagation();">
                                {!! $column->renderCell($row) !!}
                            </div>
                            @else
                            {!! $column->renderCell($row) !!}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    @include('livewire-tables::includes.empty')
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        @if ($component->currentlyReorderingIsEnabled())
    </div>
    @endif

    @if ($component->hasConfigurableAreaFor('after-table'))
    @include($component->getConfigurableAreaFor('after-table'), $component->getParametersForConfigurableArea('after-table'))
    @endif

    @if ($component->paginationIsEnabled())
    @include('livewire-tables::includes.pagination', ['rows' => $component->getRows()])
    @endif

    @if ($component->hasConfigurableAreaFor('after-toolbar'))
    @include($component->getConfigurableAreaFor('after-toolbar'), $component->getParametersForConfigurableArea('after-toolbar'))
    @endif
</div>