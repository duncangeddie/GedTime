<dialog id="DeleteProjectDialog_{{ $Project->id }}" class="DeleteProjectDialog">
    <div class="DeleteProjectModal">
        <div class="DeleteProjectModalHeader">
            <h2 class="DeleteProjectModalTitle">Delete Project</h2>
            <p class="DeleteProjectModalText">
                Are you sure you want to delete <strong>{{ $Project->project_name }}</strong>?
            </p>
        </div>

        <form method="POST" action="{{ route('projects.delete', ['ProjectId' => $Project->id]) }}">
            @csrf

            <div class="DeleteProjectModalActions">
                <button
                    type="button"
                    class="DeleteProjectCancelButton"
                    onclick="document.getElementById('DeleteProjectDialog_{{ $Project->id }}').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="DeleteProjectSubmitButton">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>
