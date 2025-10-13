<!-- Filters -->
<p class="font-medium">@lang('general.filters'):</p>
<div class="flex flex-row flex-wrap justify-between items-center pb-4">

    <div class="flex flex-row w-80 gap-3 items-center relative">
        @if (!$users->isEmpty())
            <!-- Filter -->
            <label for="userFilter" class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
            <input type="text" id="userFilter" name="userFilter"
                class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                placeholder="@lang('general.enterSearchUser')">
            <button id="clear-user-filter"
                class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                </svg>
            </button>
        @endif
    </div>

    <div class="flex flex-row gap-3 items-center">
        <div class="flex flex-wrap gap-2 items-center">
            <button
                class="all-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                id="selectAllButton">@lang('general.userFilters.all')</button>

            <!-- Gender -->
            {{-- <button type="button"
                class="all-male-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                id="selectAllMaleButton">@lang('general.userFilters.allMale')</button>
            <button type="button"
                class="all-female-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                id="selectAllFemaleButton">@lang('general.userFilters.allFemale')</button>
            <button type="button"
                class="all-other-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                id="selectAllOtherButton">@lang('general.userFilters.allOther')</button> --}}
        </div>

        <!-- Age -->
        <div class="flex flex-row items-center gap-2">
            <label for="ageFrom" class="block text-sm font-bold text-sky-700">@lang('general.userFilters.ageFrom'):</label>
            <input type="number" id="ageFrom" name="ageFrom" min="18" max="80"
                class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-16">
            <label for="ageTo" class="block text-sm font-bold text-sky-700">@lang('general.userFilters.ageTo')</label>
            <input type="number" id="ageTo" name="ageTo" min="18" max="80"
                class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-16">
        </div>
    </div>

</div>

<div class="pt-2 pb-4 shadow">
    <p class="font-bold pb-2 pl-2">@lang('general.totalUser'): <span id="totalSelectedUsers">0</span>
    </p>
    <table id="user-table" class="w-full text-center">
        <thead class="bg-gray-100">
            <tr class="border-b-2 border-gray-300">
                <th class="py-2 px-4"></th> <!-- Colonna per le checkbox -->
                <th class="py-2 px-4">@lang('general.user.name')</th>
                <th class="py-2 px-4">@lang('general.user.surname')</th>
                <th class="py-2 px-4">@lang('general.user.dob')</th>
                <th class="py-2 px-4">@lang('general.user.gender')</th>
                <th class="py-2 px-4">@lang('general.user.companyRole')</th>
                <th class="py-2 px-4">@lang('general.user.email')</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach ($users as $user)
                <tr class="hover:bg-gray-100 border-b border-gray-200">
                    <td class="py-2 px-4">
                        <input id="{{ $user->id }}" type="checkbox" class="user-checkbox"
                            data-user-id="{{ $user->id }}" data-gender="{{ $user->gender }}">
                    </td>
                    <td class="py-2 px-4">{{ $user->name }}</td>
                    <td class="py-2 px-4">{{ $user->surname }}</td>
                    <td class="py-2 px-4">
                        {{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4">{{ $user->gender }}</td>
                    <td class="py-2 px-4">{{ $user->company_role }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginator controls -->
    @include('layouts.partials.pagination-controls')
</div>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script src="{{ asset('js/usersSelection.js') }}"></script>
