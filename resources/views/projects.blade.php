<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="{{ $ProjectsPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo])

            <main class="{{ $ProjectsPageMainClass }}">
                <div class="{{ $ProjectsPageContentClass }}">
                    <div class="{{ $ProjectsPageActionsClass }}">
                        <button
                            type="button"
                            class="{{ $ProjectsPageButtonClass }}"
                            onclick="document.getElementById('AddProjectDialog').showModal()"
                        >
                            {{ $AddProjectButtonLabel }}
                        </button>
                    </div>

                    @if (session('SuccessMessage'))
                        <div class="{{ $ProjectsPageMessageClass }}">
                            {{ session('SuccessMessage') }}
                        </div>
                    @endif

                    @if (session('ErrorMessage'))
                        <div class="{{ $ProjectsPageErrorMessageClass }}">
                            {{ session('ErrorMessage') }}
                        </div>
                    @endif

                    <div class="{{ $ProjectsTableSectionClass }}">
                        <div class="{{ $ProjectsTableWrapperClass }}">
                            <table class="{{ $ProjectsTableClass }}">
                                <thead class="{{ $ProjectsTableHeadClass }}">
                                    <tr>
                                        @foreach ($ProjectsTableColumns as $ProjectsTableColumn)
                                            <th class="{{ $ProjectsTableHeadingClass }}">
                                                {{ $ProjectsTableColumn }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="{{ $ProjectsTableBodyClass }}">
                                    @forelse ($Projects as $Project)
                                        <tr class="{{ $ProjectsTableRowClass }}">
                                            <td class="{{ $ProjectsTableCellClass }}" data-label="Project Name">
                                                {{ $Project->project_name }}
                                            </td>

                                            <td class="{{ $ProjectsTableCellClass }}" data-label="Status">
                                                {{ $Project->status }}
                                            </td>

                                            <td class="{{ $ProjectsTableCellClass }} {{ $ProjectsTableActionCellClass }}" data-label="Actions">
                                                <div class="{{ $ProjectsTableActionButtonsClass }}">
                                                    <button
                                                        type="button"
                                                        class="{{ $ProjectsTableActionButtonClass }}"
                                                        onclick="document.getElementById('EditProjectDialog_{{ $Project->id }}').showModal()"
                                                    >
                                                        {{ $EditProjectButtonLabel }}
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="{{ $ProjectsTableDeleteButtonClass }}"
                                                        onclick="document.getElementById('DeleteProjectDialog_{{ $Project->id }}').showModal()"
                                                    >
                                                        {{ $DeleteProjectButtonLabel }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="{{ $ProjectsTableRowClass }}">
                                            <td colspan="{{ count($ProjectsTableColumns) }}" class="{{ $ProjectsTableEmptyCellClass }}">
                                                {{ $ProjectsTableEmptyMessage }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @include('components.add-project')

                @foreach ($Projects as $Project)
                    @include('components.edit-project', ['Project' => $Project])
                    @include('components.delete-project', ['Project' => $Project])
                @endforeach
            </main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>

        @if ($errors->has('project_name') || $errors->has('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const EditProjectId = @json(old('edit_project_id'));

                    if (EditProjectId) {
                        const EditProjectDialog = document.getElementById(`EditProjectDialog_${EditProjectId}`);

                        if (EditProjectDialog) {
                            EditProjectDialog.showModal();
                        }

                        return;
                    }

                    const AddProjectDialog = document.getElementById('AddProjectDialog');

                    if (AddProjectDialog) {
                        AddProjectDialog.showModal();
                    }
                });
            </script>
        @endif
    </body>
</html>
