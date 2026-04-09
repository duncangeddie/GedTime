<dialog id="AddProjectDialog" class="AddProjectDialog">
    <div class="AddProjectModal">
        <div class="AddProjectModalHeader">
            <h2 class="AddProjectModalTitle">Add Project</h2>
            <p class="AddProjectModalText">Create a new project for your account.</p>
        </div>

        <form method="POST" action="{{ route('projects.add') }}" class="AddProjectForm">
            @csrf

            <div class="AddProjectField">
                <label for="project_name" class="AddProjectLabel">
                    Project name
                </label>

                <input
                    id="project_name"
                    name="project_name"
                    type="text"
                    value="{{ old('project_name') }}"
                    placeholder="Enter project name"
                    class="AddProjectInput"
                    required
                >

                @error('project_name')
                    <p class="AddProjectError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddProjectField">
                <label for="status" class="AddProjectLabel">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="AddProjectSelect"
                    required
                >
                    <option value="">Select status</option>
                    <option value="Not started" @selected(old('status') === 'Not started')>Not started</option>
                    <option value="In progress" @selected(old('status') === 'In progress')>In progress</option>
                    <option value="On hold" @selected(old('status') === 'On hold')>On hold</option>
                    <option value="Completed" @selected(old('status') === 'Completed')>Completed</option>
                    <option value="Cancelled" @selected(old('status') === 'Cancelled')>Cancelled</option>
                </select>

                @error('status')
                    <p class="AddProjectError">{{ $message }}</p>
                @enderror
            </div>

            <div class="AddProjectModalActions">
                <button
                    type="button"
                    class="AddProjectCancelButton"
                    onclick="document.getElementById('AddProjectDialog').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="AddProjectSubmitButton">
                    Save Project
                </button>
            </div>
        </form>
    </div>
</dialog>
