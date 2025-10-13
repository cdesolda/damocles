<section>
    <x-input-label for="groupIdSelect" class="text-md block font-medium text-sky-700"
        :value="__('email.filterBy.group') . ($groups->isEmpty() ? ' (' . __('email.groups.noGroupsShort') . ' )': '')" 
        />
    <select id="groupIdSelect" name="groupId"
        class="mt-1 p-2 block w-full border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm min-w-[200px]">

        <option value="">-- @lang('general.NoFilter') --</option> <!-- Placeholder for no filter -->
        @if (!$groups->isEmpty())
            @foreach ($groups as $group)
                <option value="{{ $group->name }}" data-group="{{ $group->emails_ids }}">
                    {{ $group->name }}
                </option>
            @endforeach
        @endif
    </select>

</section>