<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('digital-twin.typeOfPrompt.typeOfPrompt'):
        </p>
    </header>

    <div class="tooltip w-full">
        <select id="promptTypeSelect" name="promptType"
            class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
            <option value="short" data-tooltip="@lang('digital-twin.typeOfPrompt.' . $tooltipType . '.short')">
                @lang('digital-twin.typeOfPrompt.short')
            </option>
            <option value="medium" data-tooltip="@lang('digital-twin.typeOfPrompt.' . $tooltipType . '.medium')">
                @lang('digital-twin.typeOfPrompt.medium')
            </option>
            <option value="detailed" data-tooltip="@lang('digital-twin.typeOfPrompt.' . $tooltipType . '.detailed')">
                @lang('digital-twin.typeOfPrompt.detailed')
            </option>
        </select>
        <span class="tooltiptext" style="width: 500px; text-align: left; bottom: 125%; left: 0%; margin-left: -10px;">
            {!! nl2br(__('digital-twin.typeOfPrompt.' . $tooltipType . '.short')) !!}
        </span>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('promptTypeSelect');
        const tooltip = select.nextElementSibling;
        const initialOption = select.options[select.selectedIndex];
        tooltip.innerHTML = nl2br(initialOption.getAttribute('data-tooltip') || 'No tooltip available');

        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tooltipText = selectedOption.getAttribute('data-tooltip');
            tooltip.innerHTML = nl2br(tooltipText) || 'No tooltip available';
            tooltip.classList.remove('hidden');
        });
    });

    function nl2br(str) {
        return str.replace(/\n/g, '<br>');
    }
</script>
