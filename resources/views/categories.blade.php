<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="{{ $CategoriesPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo, 'PageTitle' => $PageTitle])

            <main class="{{ $CategoriesPageMainClass }}">
                <div class="{{ $CategoriesPageContentClass }}">
                    <div class="{{ $CategoriesPageActionsClass }}">
                        <button
                            type="button"
                            class="{{ $CategoriesPageButtonClass }}"
                            onclick="document.getElementById('{{ $AddCategoryDialogId }}').showModal()"
                        >
                            {{ $AddCategoryButtonLabel }}
                        </button>
                    </div>

                    @if (session('SuccessMessage'))
                        <div class="{{ $CategoriesPageMessageClass }}">
                            {{ session('SuccessMessage') }}
                        </div>
                    @endif

                    @if (session('ErrorMessage'))
                        <div class="{{ $CategoriesPageErrorMessageClass }}">
                            {{ session('ErrorMessage') }}
                        </div>
                    @endif

                    <div class="{{ $CategoriesTableSectionClass }}">
                        <div class="{{ $CategoriesTableWrapperClass }}">
                            <table class="{{ $CategoriesTableClass }}">
                                <thead class="{{ $CategoriesTableHeadClass }}">
                                    <tr>
                                        @foreach ($CategoriesTableColumns as $CategoriesTableColumn)
                                            <th class="{{ $CategoriesTableHeadingClass }}">
                                                {{ $CategoriesTableColumn }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="{{ $CategoriesTableBodyClass }}">
                                    @forelse ($Categories as $Category)
                                        <tr class="{{ $CategoriesTableRowClass }}">
                                            <td class="{{ $CategoriesTableCellClass }}" data-label="Category Name">
                                                {{ $Category->category_name }}
                                            </td>

                                            <td class="{{ $CategoriesTableCellClass }}" data-label="Status">
                                                <span class="{{ $Category->StatusClass }}">
                                                    {{ $Category->StatusLabel }}
                                                </span>
                                            </td>

                                            <td class="{{ $CategoriesTableCellClass }} {{ $CategoriesTableActionCellClass }}" data-label="Actions">
                                                <div class="{{ $CategoriesTableActionButtonsClass }}">
                                                    @if ($Category->CanDelete)
                                                        <button
                                                            type="button"
                                                            class="{{ $CategoriesTableDeleteButtonClass }}"
                                                            onclick="document.getElementById('DeleteCategoryDialog_{{ $Category->id }}').showModal()"
                                                        >
                                                            {{ $DeleteCategoryButtonLabel }}
                                                        </button>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="{{ $CategoriesTableRowClass }}">
                                            <td colspan="{{ count($CategoriesTableColumns) }}" class="{{ $CategoriesTableEmptyCellClass }}">
                                                {{ $CategoriesTableEmptyMessage }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @include('components.add-category')

                @foreach ($Categories as $Category)
                    @if ($Category->CanDelete)
                        @include('components.delete-category', ['Category' => $Category])
                    @endif
                @endforeach
            </main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>

        @if ($errors->has($CategoryNameName))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('{{ $AddCategoryDialogId }}').showModal();
                });
            </script>
        @endif
    </body>
</html>
