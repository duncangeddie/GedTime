<dialog id="{{ $AddCategoryDialogId }}" class="AddCategoryDialog">
    <div class="AddCategoryModal">
        <div class="AddCategoryModalHeader">
            <h2 class="AddCategoryModalTitle">{{ $AddCategoryTitle }}</h2>
            <p class="AddCategoryModalText">{{ $AddCategoryText }}</p>
        </div>

        <form method="POST" action="{{ $AddCategoryFormAction }}" class="AddCategoryForm">
            @csrf

            <div class="AddCategoryField">
                <label for="{{ $CategoryNameName }}" class="AddCategoryLabel">
                    {{ $CategoryNameLabel }}
                </label>

                <input
                    id="{{ $CategoryNameName }}"
                    name="{{ $CategoryNameName }}"
                    type="text"
                    value="{{ old($CategoryNameName) }}"
                    placeholder="{{ $CategoryNamePlaceholder }}"
                    class="AddCategoryInput"
                    required
                >

                @error($CategoryNameName)
                    <p class="AddCategoryError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddCategoryModalActions">
                <button
                    type="button"
                    class="AddCategoryCancelButton"
                    onclick="document.getElementById('{{ $AddCategoryDialogId }}').close()"
                >
                    {{ $AddCategoryCancelLabel }}
                </button>

                <button type="submit" class="AddCategorySubmitButton">
                    {{ $AddCategorySubmitLabel }}
                </button>
            </div>
        </form>
    </div>
</dialog>
