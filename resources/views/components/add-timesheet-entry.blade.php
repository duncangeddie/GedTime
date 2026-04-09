<dialog id="{{ $AddTimesheetEntryDialogId }}" class="AddTimesheetEntryDialog">
    <div class="AddTimesheetEntryModal">
        <div class="AddTimesheetEntryModalHeader">
            <h2 class="AddTimesheetEntryModalTitle">{{ $AddTimesheetEntryTitle }}</h2>
            <p class="AddTimesheetEntryModalText">{{ $AddTimesheetEntryText }}</p>
        </div>

        <form method="POST" action="{{ $AddTimesheetEntryFormAction }}" class="AddTimesheetEntryForm">
            @csrf

            <div class="AddTimesheetEntryField">
                <label for="{{ $ProjectFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $ProjectFieldLabel }}
                </label>

                <select
                    id="{{ $ProjectFieldName }}"
                    name="{{ $ProjectFieldName }}"
                    class="AddTimesheetEntrySelect"
                    required
                >
                    <option value="">{{ $ProjectFieldPlaceholder }}</option>

                    @foreach ($Projects as $Project)
                        <option value="{{ $Project->id }}" @selected(old($ProjectFieldName) == $Project->id)>
                            {{ $Project->project_name }}
                        </option>
                    @endforeach
                </select>

                @if (! $HasProjects)
                    <p class="AddTimesheetEntryHelperText">{{ $NoProjectsMessage }}</p>
                @endif

                @error($ProjectFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryField">
                <label for="{{ $CategoryFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $CategoryFieldLabel }}
                </label>

                <select
                    id="{{ $CategoryFieldName }}"
                    name="{{ $CategoryFieldName }}"
                    class="AddTimesheetEntrySelect"
                    required
                >
                    <option value="">{{ $CategoryFieldPlaceholder }}</option>

                    @foreach ($Categories as $Category)
                        <option value="{{ $Category->id }}" @selected(old($CategoryFieldName) == $Category->id)>
                            {{ $Category->category_name }}
                        </option>
                    @endforeach
                </select>

                @if (! $HasCategories)
                    <p class="AddTimesheetEntryHelperText">{{ $NoCategoriesMessage }}</p>
                @endif

                @error($CategoryFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryField">
                <div class="AddTimesheetEntryBreakRow">
                    <label for="{{ $BreakFieldName }}" class="AddTimesheetEntryCheckboxLabel">
                        <input
                            id="{{ $BreakFieldName }}"
                            name="{{ $BreakFieldName }}"
                            type="checkbox"
                            value="1"
                            class="AddTimesheetEntryCheckbox"
                            @checked(old($BreakFieldName))
                        >
                        <span>{{ $BreakFieldLabel }}</span>
                    </label>
                </div>
            </div>

            <div class="AddTimesheetEntryField">
                <label for="{{ $DateFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $DateFieldLabel }}
                </label>

                <input
                    id="{{ $DateFieldName }}"
                    name="{{ $DateFieldName }}"
                    type="date"
                    value="{{ old($DateFieldName) }}"
                    class="AddTimesheetEntryInput"
                    required
                >

                @error($DateFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryField">
                <label for="{{ $StartFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $StartFieldLabel }}
                </label>

                <input
                    id="{{ $StartFieldName }}"
                    name="{{ $StartFieldName }}"
                    type="time"
                    value="{{ old($StartFieldName) }}"
                    class="AddTimesheetEntryInput"
                    required
                >

                @error($StartFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryField">
                <label for="{{ $EndFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $EndFieldLabel }}
                </label>

                <input
                    id="{{ $EndFieldName }}"
                    name="{{ $EndFieldName }}"
                    type="time"
                    value="{{ old($EndFieldName) }}"
                    class="AddTimesheetEntryInput"
                    required
                >

                @error($EndFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryField">
                <label for="{{ $TaskFieldName }}" class="AddTimesheetEntryLabel">
                    {{ $TaskFieldLabel }}
                </label>

                <input
                    id="{{ $TaskFieldName }}"
                    name="{{ $TaskFieldName }}"
                    type="text"
                    value="{{ old($TaskFieldName) }}"
                    placeholder="{{ $TaskFieldPlaceholder }}"
                    class="AddTimesheetEntryInput"
                    required
                >

                @error($TaskFieldName)
                    <p class="AddTimesheetEntryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddTimesheetEntryModalActions">
                <button
                    type="button"
                    class="AddTimesheetEntryCancelButton"
                    onclick="document.getElementById('{{ $AddTimesheetEntryDialogId }}').close()"
                >
                    {{ $AddTimesheetEntryCancelLabel }}
                </button>

                <button type="submit" class="AddTimesheetEntrySubmitButton" @disabled(! $HasCategories)>
                    {{ $AddTimesheetEntrySubmitLabel }}
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const BreakCheckbox = document.getElementById('{{ $BreakFieldName }}');
        const ProjectSelect = document.getElementById('{{ $ProjectFieldName }}');
        const CategorySelect = document.getElementById('{{ $CategoryFieldName }}');

        if (! BreakCheckbox || ! ProjectSelect || ! CategorySelect) {
            return;
        }

        const ToggleBreakFields = function () {
            if (BreakCheckbox.checked) {
                ProjectSelect.value = '';
                ProjectSelect.disabled = true;
                ProjectSelect.removeAttribute('required');

                CategorySelect.value = '';
                CategorySelect.disabled = true;
                CategorySelect.removeAttribute('required');
            } else {
                ProjectSelect.disabled = false;
                ProjectSelect.setAttribute('required', 'required');

                CategorySelect.disabled = false;
                CategorySelect.setAttribute('required', 'required');
            }
        };

        BreakCheckbox.addEventListener('change', ToggleBreakFields);
        ToggleBreakFields();
    });
</script>
