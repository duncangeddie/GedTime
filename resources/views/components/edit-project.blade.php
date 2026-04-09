<dialog id="EditProjectDialog_{{ $Project->id }}" class="EditProjectDialog">
    <div class="EditProjectModal">
        <div class="EditProjectModalHeader">
            <h2 class="EditProjectModalTitle">Edit Project</h2>
            <p class="EditProjectModalText">Update the project details below.</p>
        </div>

        <form method="POST" action="{{ route('projects.edit', ['ProjectId' => $Project->id]) }}" class="EditProjectForm">
            @csrf

            <input type="hidden" name="edit_project_id" value="{{ $Project->id }}">

            <div class="EditProjectField">
                <label for="edit_project_name_{{ $Project->id }}" class="EditProjectLabel">
                    Project name
                </label>

                <input
                    id="edit_project_name_{{ $Project->id }}"
                    name="project_name"
                    type="text"
                    value="{{ old('edit_project_id') == $Project->id ? old('project_name', $Project->project_name) : $Project->project_name }}"
                    placeholder="Enter project name"
                    class="EditProjectInput"
                    required
                >

                @if (old('edit_project_id') == $Project->id)
                    @error('project_name')
                        <p class="EditProjectError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditProjectField">
                <label for="edit_status_{{ $Project->id }}" class="EditProjectLabel">
                    Status
                </label>

                <select
                    id="edit_status_{{ $Project->id }}"
                    name="status"
                    class="EditProjectSelect"
                    required
                >
                    <option value="">Select status</option>
                    <option value="Not started" @selected((old('edit_project_id') == $Project->id ? old('status', $Project->status) : $Project->status) === 'Not started')>Not started</option>
                    <option value="In progress" @selected((old('edit_project_id') == $Project->id ? old('status', $Project->status) : $Project->status) === 'In progress')>In progress</option>
                    <option value="On hold" @selected((old('edit_project_id') == $Project->id ? old('status', $Project->status) : $Project->status) === 'On hold')>On hold</option>
                    <option value="Completed" @selected((old('edit_project_id') == $Project->id ? old('status', $Project->status) : $Project->status) === 'Completed')>Completed</option>
                    <option value="Cancelled" @selected((old('edit_project_id') == $Project->id ? old('status', $Project->status) : $Project->status) === 'Cancelled')>Cancelled</option>
                </select>

                @if (old('edit_project_id') == $Project->id)
                    @error('status')
                        <p class="EditProjectError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditProjectModalActions">
                <button
                    type="button"
                    class="EditProjectCancelButton"
                    onclick="document.getElementById('EditProjectDialog_{{ $Project->id }}').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="EditProjectSubmitButton">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</dialog>
