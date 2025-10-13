<!-- Paginator Controls -->
<div id="pagination-controls" class="flex justify-around items-center mt-4">
    <div class="flex justify-center w-1/3">
        <x-primary-button id="prevPage">@lang('general.previous')</x-primary-button>
    </div>
    <div class="flex flex-row justify-center items-center w-1/3 gap-4">
        <span id="pageIndicator" class="text-gray-700"></span>
        <span id="totalRows" class="text-gray-500 text-sm"></span>
        <select id="rowsPerPage" class="border border-gray-300 rounded-md shadow-sm">
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
    </div>
    <div class="flex justify-center w-1/3">
        <x-primary-button id="nextPage">@lang('general.next')</x-primary-button>
    </div>
</div>
