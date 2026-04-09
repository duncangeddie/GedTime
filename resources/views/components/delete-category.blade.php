<dialog id="DeleteCategoryDialog_{{ $Category->id }}" class="DeleteCategoryDialog">
    <div class="DeleteCategoryModal">
        <div class="DeleteCategoryModalHeader">
            <h2 class="DeleteCategoryModalTitle">Delete Category</h2>
            <p class="DeleteCategoryModalText">
                Are you sure you want to delete <strong>{{ $Category->category_name }}</strong>?
            </p>
        </div>

        <form method="POST" action="{{ route('categories.delete', ['CategoryId' => $Category->id]) }}">
            @csrf

            <div class="DeleteCategoryModalActions">
                <button
                    type="button"
                    class="DeleteCategoryCancelButton"
                    onclick="document.getElementById('DeleteCategoryDialog_{{ $Category->id }}').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="DeleteCategorySubmitButton">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>
