@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg--white br--solid radius--base p-16">
                <div class="card-body p-0">
                    <div class="row gy-4">
                        {{-- Selected Menu Items --}}
                        <div class="col-lg-6">
                            <div class="card bg--white br--solid radius--base p-16">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __($menu->name) }} @lang('Menu')</h3>
                                    <small>@lang('Drop menu items here to build or rearrange the menu.')</small>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.menu.assign.item.submit', $menu->id) }}" method="post">
                                        @csrf
                                        <ol class="simple_with_drop vertical" id="selected-sections">
                                            @foreach ($assigned->items as $data)
                                                <li draggable="true" data-id="{{ $data->id }}" class="draggable-item">
                                                    <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                                    <span class="ms-2">{{ __($data->title) }}</span>
                                                   <div class="edit-btn--wrap d-flex gap-2 align-items-center position-absolute">
                                                        <i class="fa-regular fa-trash-can remove-icon"></i>
                                                    </div>
                                                    <input type="hidden" name="menu_items[]" value="{{ $data->id }}">
                                                </li>
                                            @endforeach
                                        </ol>
                                        <div class="form-group text-end mt-3">
                                            <button type="submit" class="btn btn--primary">@lang('Update')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Available Menu Items --}}
                        <div class="col-lg-6">
                            <div class="card bg--white br--solid radius--base p-16">
                                <div class="card-header mb-4">
                                    <h3 class="card-title">@lang('Available Menu Items')</h3>
                                    <small>@lang('Drag items from the left and drop them into the menu area on the right.')</small>
                                </div>
                                <div class="card-body p-0">
                                    <ol class="simple_with_no_drop vertical" id="available-sections">
                                        @foreach ($items as $data)
                                            @php
                                                $selectedItems = $menu->items->pluck('id')->toArray();
                                            @endphp

                                            @if (!in_array($data->id, $selectedItems))
                                                <li draggable="true" data-id="{{ $data->id }}" class="draggable-item two">
                                                    <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                                    <span class="ms-2">{{ __($data->title) }}</span>
                                                    {{-- remove icon removed --}}
                                                    <input type="hidden" name="menu_items[]" value="{{ $data->id }}">
                                                </li>
                                            @endif
                                        @endforeach
                                    </ol>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
   function initDragDrop(availableSelector, selectedSelector) {
    const $available = $(availableSelector);
    const $selected = $(selectedSelector);
    let draggedItem = null;
    let $placeholder = $('<li class="placeholder" style="height:60px;border:2px dashed #aaa;margin:8px 0;border-radius:6px;"></li>');

    // Drag start
    $(document).on('dragstart', '.draggable-item', function () {
        draggedItem = $(this);
        draggedItem.addClass('dragging');
    });

    // Drag end
    $(document).on('dragend', '.draggable-item', function () {
        draggedItem.removeClass('dragging');
        $placeholder.remove();
        draggedItem = null;
    });

    // Drag over on selected
    $selected.on('dragover', function (e) {
        e.preventDefault();
        if (!draggedItem) return;

        if ($selected.find('.draggable-item').length === 0) {
            if (!$selected.find('.placeholder').length) {
                $selected.append($placeholder);
            }
            return;
        }

        let $target = $(e.target).closest('.draggable-item');
        if ($target.length && $target[0] !== draggedItem[0]) {
            if ($target.index() < draggedItem.index()) {
                $target.before($placeholder);
            } else {
                $target.after($placeholder);
            }
        } else {
            if (!$selected.find('.placeholder').length) {
                $selected.append($placeholder);
            }
        }
    });

    // Drop handler
    $selected.on('drop', function (e) {
        e.preventDefault();
        if (!draggedItem) return;

        if ($placeholder.parent().length) {
            $placeholder.replaceWith(draggedItem);
        } else {
            $selected.append(draggedItem);
        }

        // Ensure remove icon exists
        if (!draggedItem.find('.remove-icon').length) {
            let $wrap = draggedItem.find('.edit-btn--wrap');
            if (!$wrap.length) {
                $wrap = $('<div class="edit-btn--wrap d-flex gap-2 align-items-center position-absolute"></div>');
                draggedItem.append($wrap);
            }
            $wrap.append('<i class="fa-regular fa-trash-can remove-icon"></i>');
        }

        updateSelectedInputs();
    });

    // Available drop (send back)
    $available.on('dragover', function (e) { e.preventDefault(); });
    $available.on('drop', function (e) {
        e.preventDefault();
        if (!draggedItem) return;
        $available.append(draggedItem);
        draggedItem.find('.remove-icon').remove();
        updateSelectedInputs();
    });

    // Delete click
    $(document).on('click', '.remove-icon', function () {
        let $item = $(this).closest('li');
        $available.append($item);
        $item.find('.remove-icon').remove();
        updateSelectedInputs();
    });

    function updateSelectedInputs() {
        $selected.find('input[name="menu_items[]"]').remove();
        $selected.find('.draggable-item').each(function () {
            $selected.append(`<input type="hidden" name="menu_items[]" value="${$(this).data('id')}">`);
        });
    }
}

$(function () {
    'use strict';
    initDragDrop('#available-sections', '#selected-sections');
});

    </script>
@endpush

@push('style')
    <style>
        ol {
            list-style: none;
            padding-left: 0;
            min-height: 100px;
        }

        ol li.draggable-item {
            background: #f8f9fa;
            padding: 16px 26px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            color: #373737 !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 16px;
            cursor: grab;
            transition: background 0.2s, transform 0.2s;
            position: relative;

            span {
                color: #373737 !important;
            }

            .fa-solid.fa-arrows-up-down-left-right {
                color: #373737 !important;
            }

            .edit-btn--wrap {
                top: 10px;
                right: 10px;
            }
        }

        ol li.draggable-item:hover {
            background: #e9ecef;
        }

        ol li.draggable-item.dragging {
            opacity: 0.7;
            transform: scale(0.98);
        }

        .remove-icon {
            color: #e53935;
            cursor: pointer;
            font-size: 18px;
            margin-left: 8px;
        }

        .remove-icon:hover {
            color: #b71c1c;
        }

        .simple_with_drop {
            border: 2px dashed #ddd;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            justify-content: start;
            align-items: flex-start;
            gap: 16px;

            .draggable-item {
                width: 100%;

                &.two {
                    width: 100% !important;
                }

                .edit-btn--wrap {
                    top: 10px;
                    right: 10px;
                }
            }
        }

        .simple_with_no_drop {
            border: 2px dashed #ddd;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            align-items: flex-start;
            gap: 16px;

            .draggable-item {
                width: 31.3%;
            }
        }

        @media screen and (max-width: 1440px) {
            ol li.draggable-item {
                &.two {
                    width: 47%;
                }
            }
        }
    </style>
@endpush
