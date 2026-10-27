{{-- Shared by tasks.create and tasks.edit. Expects $task (null when creating). --}}
@php
    $priorities = ['P1' => 'Urgent', 'P2' => 'High', 'P3' => 'Medium', 'P4' => 'Low'];
    $statuses = ['pending' => 'To do', 'in_progress' => 'In progress', 'completed' => 'Done'];
    $currentPriority = old('priority', $task->priority ?? 'P4');
    $currentStatus = old('status', $task->status ?? 'pending');
@endphp

<div class="space-y-6">
    <div>
        <label for="title" class="label">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required maxlength="255" autofocus
               class="input" placeholder="What needs to get done?">
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <label for="description" class="label">Description <span class="font-normal text-ink-400">(optional)</span></label>
        <textarea id="description" name="description" rows="3" class="input resize-y" placeholder="Add notes or details">{{ old('description', $task->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="category" class="label">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category', $task->category ?? 'General') }}" maxlength="50" class="input" list="category-suggestions">
            <datalist id="category-suggestions">
                <option value="General"><option value="Study"><option value="Work"><option value="Personal">
            </datalist>
            <x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>

        <div>
            <label for="due_date" class="label">Due date</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ?? '') }}" class="input">
            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
        </div>
    </div>

    <fieldset>
        <legend class="label">Priority</legend>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach ($priorities as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="priority" value="{{ $value }}" class="peer sr-only" @checked($currentPriority === $value)>
                    <span class="flex flex-col items-center rounded-xl border border-ink-200 px-3 py-2.5 text-center transition hover:border-ink-300 peer-checked:border-brand-600 peer-checked:bg-brand-50 peer-checked:ring-1 peer-checked:ring-brand-600 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-600">
                        <span class="text-sm font-semibold text-ink-900">{{ $value }}</span>
                        <span class="text-xs text-ink-500">{{ $label }}</span>
                    </span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
    </fieldset>

    <fieldset>
        <legend class="label">Status</legend>
        <div class="inline-flex w-full rounded-xl border border-ink-200 bg-ink-50 p-1 sm:w-auto">
            @foreach ($statuses as $value => $label)
                <label class="flex-1 cursor-pointer sm:flex-none">
                    <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" @checked($currentStatus === $value)>
                    <span class="block rounded-lg px-4 py-1.5 text-center text-sm font-medium text-ink-500 transition hover:text-ink-900 peer-checked:bg-white peer-checked:text-ink-900 peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-brand-600">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </fieldset>
</div>
